<?php

$customHandlers[] = "
    updatePackage : function(task) {
        var packageName = Ext.get('migxpm_packageName').dom.value;
        var schema = '';
        if (task == 'saveSchema'){
            schema = Ext.get('migxpm_schema').dom.value;
        }
        MODx.Ajax.request({
            url: Migx.config.connectorUrl
            ,params: {
                action: 'mgr/packagemanager/packagemanager'
				,task: task
                ,packageName : packageName
                ,schema : schema
            }
            ,listeners: {
                'success': {fn:function(r){this.updatePackageSuccess(r)},scope:this}
            }
        });
    }	
";

$customHandlers[] = "
    updatePackageSuccess : function(r) {
        
        if (r.object.content){
            //console.log(r.object.content);
            Ext.get('migxpm_schema').dom.value = r.object.content;
            
            
            return;
        }
        alert ('success');
        
    }	
";

$tabTemplate = $this->config['templatesPath'] . 'mgr/packagemanagertab.tpl';