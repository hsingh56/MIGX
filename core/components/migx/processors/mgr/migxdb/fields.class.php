<?php

/**
 * Loads the TV panel for MIGX.
 *
 * Note: This page is not to be accessed directly.
 *
 * @package migx
 * @subpackage processors
 */

class migxFormProcessor extends modProcessor
{

    public function process()
    {
        //require_once dirname(dirname(dirname(__file__))) . '/model/migx/migx.class.php';
        //$migx = new Migx($this->modx);
        $modx = &$this->modx;

        require_once dirname(dirname(dirname(dirname(__file__)))) . '/model/migx/migxformcontroller.class.php';
        $controller = new MigxFormController($this->modx);
        $this->modx->controller = &$controller;

        $this->modx->getService('smarty', 'smarty.modSmarty');
        $scriptProperties = $this->getProperties();
        //$controller->loadControllersPath();

        // we will need a way to get a context-key, if in CMP-mode, from config, from dataset..... thoughts??
        // can be overridden in custom-processors for now, but whats with the preparegrid-method and working-context?
        // ok let's see when we need this.
        $this->modx->migx->working_context = 'web';

        if ($this->modx->resource = $this->modx->getObject('modResource', $scriptProperties['resource_id'])) {
            $this->modx->migx->working_context = $this->modx->resource->get('context_key');

            //$_REQUEST['id']=$scriptProperties['resource_id'];
        }

        $controller->loadTemplatesPath();
        $controller->setPlaceholder('_config', $this->modx->config);
        $task = $this->modx->migx->getTask();
        $filename = str_replace('.class', '', basename(__file__));
        $processorspath = dirname(dirname(__file__)). '/';

        if ($processor_file = $this->modx->migx->findProcessor($processorspath, $filename)) {
            include_once ($processor_file);
        }


        //$object = $this->modx->getObject('Angebote',$scriptProperties['angebot']);
        //if (empty($object)) return $this->modx->error->failure($this->modx->lexicon('quip.thread_err_nf'));
        //if (!$thread->checkPolicy('view')) return $this->modx->error->failure($this->modx->lexicon('access_denied'));

        //return $this->modx->error->success('',$angebot);

        //echo '<pre>'.print_r($angebot->toArray(),1).'</pre>';


        $this->modx->migx->loadConfigs();
        $tabs = $this->modx->migx->getTabs();
        $fieldid = 0;
        $allfields[] = array();
        $categories = array();
        $this->modx->migx->createForm($tabs, $record, $allfields, $categories, $scriptProperties);

        $controller->setPlaceholder('fields', $this->modx->toJSON($allfields));
        $controller->setPlaceholder('customconfigs', $this->modx->migx->customconfigs);
        $controller->setPlaceholder('object', $object);
        $controller->setPlaceholder('categories', $categories);
        //$controller->setPlaceholder('win_id', $scriptProperties['tv_id']);
        $controller->setPlaceholder('win_id', isset($this->modx->migx->customconfigs['win_id']) ? $this->modx->migx->customconfigs['win_id'] : $scriptProperties['tv_id']);
        //$c->setPlaceholder('id_update_window', 'modx-window-midb-grid-update');

        if (!empty($_REQUEST['showCheckbox'])) {
            $controller->setPlaceholder('showCheckbox', 1);
        }


        return $controller->process($scriptProperties);

    }
}
return 'migxFormProcessor';
