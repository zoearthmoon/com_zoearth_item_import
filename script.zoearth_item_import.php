<?php
defined('_JEXEC') or die ;

class Com_Loan_CustomerInstallerScript
{
    
    public $comName = "借貸申請記錄資料管理";
    public $comDir  = "com_loan_customer";
    
    public function postflight($type, $parent)
    {
        $db = JFactory::getDBO();
        $status = new stdClass;
        $status->modules = array();
        $status->plugins = array();
        $src = $parent->getParent()->getPath('source');
        $manifest = $parent->getParent()->manifest;
        $plugins = $manifest->xpath('plugins/plugin');
        foreach ($plugins as $plugin)
        {
            $name = (string)$plugin->attributes()->plugin;
            $group = (string)$plugin->attributes()->group;
            $path = $src.'/plugins/'.$group;
            if (JFolder::exists($src.'/plugins/'.$group.'/'.$name))
            {
                $path = $src.'/plugins/'.$group.'/'.$name;
            }
            $installer = new JInstaller;
            $result = $installer->install($path);
            if ($result && $group != 'finder' && $group != 'josetta_ext')
            {
                if (JFile::exists(JPATH_SITE.'/plugins/'.$group.'/'.$name.'/'.$name.'.xml'))
                {
                    JFile::delete(JPATH_SITE.'/plugins/'.$group.'/'.$name.'/'.$name.'.xml');
                }
            }
            $query = "UPDATE #__extensions SET enabled=1 WHERE type='plugin' AND element=".$db->Quote($name)." AND folder=".$db->Quote($group);
            $db->setQuery($query);
            $db->query();
            $status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
        }        
        $modules = $manifest->xpath('modules/module');
        foreach ($modules as $module)
        {
            $name = (string)$module->attributes()->module;
            $client = (string)$module->attributes()->client;
            if (is_null($client))
            {
                $client = 'site';
            }
            ($client == 'administrator') ? $path = $src.'/administrator/modules/'.$name : $path = $src.'/modules/'.$name;
            
            if($client == 'administrator')
            {
                $db->setQuery("SELECT id FROM #__modules WHERE `module` = ".$db->quote($name));
                $isUpdate = (int)$db->loadResult();
            }
            
            $installer = new JInstaller;
            $result = $installer->install($path);
            if ($result)
            {
                $root = $client == 'administrator' ? JPATH_ADMINISTRATOR : JPATH_SITE;
                //if (JFile::exists($root.'/modules/'.$name.'/'.$name.'.xml'))
                //{
                //    JFile::delete($root.'/modules/'.$name.'/'.$name.'.xml');
                //}
                JFile::move($root.'/modules/'.$name.'/'.$name.'.xml', $root.'/modules/'.$name.'/'.$name.'.xml');
            }
            $status->modules[] = array('name' => $name, 'client' => $client, 'result' => $result);
            if($client == 'administrator' && !$isUpdate)
            {
                $db->setQuery("SELECT id FROM #__modules WHERE `module` = ".$db->quote($name));
                $id = (int)$db->loadResult();

                $db->setQuery("INSERT IGNORE INTO #__modules_menu (`moduleid`,`menuid`) VALUES (".$id.", 0)");
                $db->query();
            }
        }
        $this->installationResults($status);
    }

    public function uninstall($parent)
    {
        $db = JFactory::getDBO();
        $status = new stdClass;
        $status->modules = array();
        $status->plugins = array();
        $manifest = $parent->getParent()->manifest;
        $plugins = $manifest->xpath('plugins/plugin');
        foreach ($plugins as $plugin)
        {
            $name = (string)$plugin->attributes()->plugin;
            $group = (string)$plugin->attributes()->group;
            $query = "SELECT `extension_id` FROM #__extensions WHERE `type`='plugin' AND element = ".$db->Quote($name)." AND folder = ".$db->Quote($group);
            $db->setQuery($query);
            $extensions = $db->loadColumn();
            if (count($extensions))
            {
                foreach ($extensions as $id)
                {
                    $installer = new JInstaller;
                    $result = $installer->uninstall('plugin', $id);
                }
                $status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
            }

        }
        $modules = $manifest->xpath('modules/module');
        foreach ($modules as $module)
        {
            $name = (string)$module->attributes()->module;
            $client = (string)$module->attributes()->client;
            $db = JFactory::getDBO();
            $query = "SELECT `extension_id` FROM `#__extensions` WHERE `type`='module' AND element = ".$db->Quote($name)."";
            $db->setQuery($query);
            $extensions = $db->loadColumn();
            if (count($extensions))
            {
                foreach ($extensions as $id)
                {
                    $installer = new JInstaller;
                    $result = $installer->uninstall('module', $id);
                }
                $status->modules[] = array('name' => $name, 'client' => $client, 'result' => $result);
            }

        }
        $this->uninstallationResults($status);
    }

    public function update($type)
    {
        $db = JFactory::getDBO();
        //20140513 zoearth 這邊直接執行install.sql
        $templine = '';
        $lines = file(JPATH_ADMINISTRATOR.'/components/'.$this->comDir.'/sql/install.utf8.sql');
        foreach ($lines as $line)
        {
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;
            $templine .= $line;
            if (substr(trim($line), -1, 1) == ';')
            {
                $db->setQuery($templine);
                $db->query();
                $templine = '';
            }
        }
        
        //安裝時開啟安裝的模組功能
        $query = "UPDATE #__modules SET published = 1,position = 'cpanel' WHERE module IN (
                'mod_loan_record_chart'
                ) ";
        $db->setQuery($query);
        $db->query();
        
        //安裝時關閉用不到的模組功能
        $query = "UPDATE #__modules SET published = 0 WHERE title IN (
                'Popular Articles',
                'Recently Added Articles',
                'Logged-in Users',
                'Z2 Quick Icons (admin)',
                'Z2 Stats (admin)',
                'Quick Icons'
                ) ";
        $db->setQuery($query);
        $db->query();
        
        //20140122 zoearth 補上欄位
        $fields = $db->getTableColumns('#__loan_service');
        if (!array_key_exists('salesGuid', $fields))
        {
            $query = "ALTER TABLE #__loan_service
                    ADD `salesGuid` int(11) NOT NULL DEFAULT '0' COMMENT '業務Guid' AFTER `userGuid` ";
            $db->setQuery($query);
            $db->query();
        }
        //20140609 zoearth
        $fields = $db->getTableColumns('#__loan_record');
        if (!array_key_exists('loanSituationGuid', $fields))
        {
            $query = "ALTER TABLE #__loan_record
                    ADD `loanSituationGuid` int(11) NOT NULL DEFAULT '0' COMMENT '申請情況Guid' AFTER `loanServiceItemGuid` ";
            $db->setQuery($query);
            $db->query();
        }
        
    }
    private function installationResults($status)
    {
        $rows = 0; ?>
        <h2><?php echo $this->comName?></h2>
        <table class="adminlist table table-striped">
            <thead>
                <tr>
                    <th class="title" colspan="2">延伸模組</th>
                    <th width="30%">狀態</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            <tbody>
                <tr class="row0">
                    <td class="key" colspan="2">元件</td>
                    <td><strong>已安裝</strong></td>
                </tr>
                <?php if (count($status->modules)): ?>
                <tr>
                    <th>模組</th>
                    <th>客戶端</th>
                    <th></th>
                </tr>
                <?php foreach ($status->modules as $module): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key"><?php echo $module['name']; ?></td>
                    <td class="key"><?php echo ucfirst($module['client']); ?></td>
                    <td><strong><?php echo ($module['result'])? '已安裝':'未安裝'; ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                <?php if (count($status->plugins)): ?>
                <tr>
                    <th>外掛</th>
                    <th>群組</th>
                    <th></th>
                </tr>
                <?php foreach ($status->plugins as $plugin): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
                    <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
                    <td><strong><?php echo ($plugin['result'])? '已安裝':'未安裝'; ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php
    }
    private function uninstallationResults($status)
    {
        $rows = 0;
        ?>
        <h2><?php echo $this->comName?>(移除)</h2>
        <table class="adminlist table table-striped">
            <thead>
                <tr>
                    <th class="title" colspan="2">延伸模組</th>
                    <th width="30%">狀態</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            <tbody>
                <tr class="row0">
                    <td class="key" colspan="2">元件</td>
                    <td><strong>已安裝</strong></td>
                </tr>
                <?php if (count($status->modules)): ?>
                <tr>
                    <th>模組</th>
                    <th>客戶端</th>
                    <th></th>
                </tr>
                <?php foreach ($status->modules as $module): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key"><?php echo $module['name']; ?></td>
                    <td class="key"><?php echo ucfirst($module['client']); ?></td>
                    <td><strong><?php echo ($module['result']) ? '已移除':'未移除'; ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>

                <?php if (count($status->plugins)): ?>
                <tr>
                    <th>外掛</th>
                    <th>群組</th>
                    <th></th>
                </tr>
                <?php foreach ($status->plugins as $plugin): ?>
                <tr class="row<?php echo(++$rows % 2); ?>">
                    <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
                    <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
                    <td><strong><?php echo ($plugin['result']) ? '已移除':'未移除'; ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php
    }
    }
