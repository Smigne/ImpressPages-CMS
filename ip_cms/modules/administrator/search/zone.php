<?php
/**
 * @package ImpressPages
 *
 *
 */
namespace Modules\administrator\search;

if (!defined('FRONTEND')&&!defined('BACKEND')) exit;


require_once('element.php');


class Zone extends \Frontend\Zone {


    /**
     * Find elements of this zone.
     * @return array Element
     */
    public function getElements($language = null, $parentElementId = null, $startFrom = 1, $limit = null, $includeHidden = false, $reverseOrder = null) {
        return array();
    }


    /**
     * @param int $elementId
     * @return Element
     */
    public function getElement($elementId) {
        return new Element(null, $this->name); //default zone return element with all url and get variable combinations
    }


    /**
     * @param array $url_vars
     * @return array element
     */
    public function findElement($urlVars, $getVars) {
        /*this zone never returns error404 and in reality have no pages (elements)*/
        if(isset($getVars['q']) && trim($getVars['q'] != '')) {
            return new Element(trim($getVars['q']), $this->name);
        }else {
            return new Element(null, $this->name);
        }
    }


    
    public function getForm() {
        global $parametersMod;
        global $site;
        
        $form = new \Modules\developer\form\Form();
        $form->setMethod(\Modules\developer\form\Form::METHOD_GET);
        
        $field = new \Modules\developer\form\Field\Text(
        array(
            'name' => 'q',
            'label' => ''
        ));
        if($site->currentZone == $this->name && isset($_GET['q'])){
            $field->setDefaultValue($site->getVars['q']);
        }
        $form->addField($field);
        
        //Submit button
        $field = new \Modules\developer\form\Field\Submit(
        array(
            'name' => 'submit',
            'defaultValue' => $parametersMod->getValue('administrator','search','translations','search')
        ));
        $form->addField($field);
        $form->removeClass('ipModuleForm');
        $form->setAction($site->generateUrl(null, $this->getName()));
        
        return $form;
    }
    
    /**
     * Generate search field
     * @return string html search form.
     */
    public function generateSearchBox() {
        global $site;
        global $parametersMod;
        
        $data = array (
            'actionUrl' => $site->generateUrl(null, $this->getName())
        );
        
        
        $data['form'] = $this->getForm();
        
        $searchBox = \Ip\View::create('view/search_box.php', $data)->render();

        return $searchBox;

    }

}
