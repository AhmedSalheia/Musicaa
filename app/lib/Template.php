<?php

namespace MUSICAA\lib;


class Template
{
    private $_template_parts;
    private $_action_view;
    private $_data;
    private $_type;

    public function __construct(array $parts)
    {
        $this->_template_parts = $parts;
    }

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function setActionViewFile($actionView){
        $this->_action_view = $actionView;
    }

    public function setAppData($data){
        $this->_data = $data;
    }

    private function renderTemplateHeaderStart(){
        extract($this->_data);
        require_once TEMPLATE_PATH[$this->_type] . 'templateheaderstart.php';
    }

    private function renderTemplateHeaderEnd(){
        extract($this->_data);
        require_once TEMPLATE_PATH[$this->_type] . 'templateheaderend.php';
    }

    private function renderTemplateFooter(){
        extract($this->_data);
        require_once TEMPLATE_PATH[$this->_type] . 'templatefooter.php';
    }

    private function renderTemplateBlocks($block,$allow){
        if (!array_key_exists('template', $this->_template_parts)){
            trigger_error('Sorry There Is No Template Blocks In Your Project',E_USER_WARNING);
        }else{
            extract($this->_data);

            $parts = array_merge($this->_template_parts['template'][$this->_type],$allow);
            if (!empty($parts)){
                foreach ($parts as $partKey => $file){

                    if (in_array($partKey,$block,true))
                    {
                        continue;
                    }

                    if ($partKey === ':view'){
                        require_once $this->_action_view;
                    }else{
                        require_once $file;
                    }
                }
            }
        }
    }

    private function renderHeaderResources($block,$allow){
        extract($this->_data);

        $output = '';
        if (!array_key_exists('header', $this->_template_parts)){
            trigger_error('Sorry There Is No Header Resources Blocks In Your Project',E_USER_WARNING);
        }else{
            $resources = $this->_template_parts['header'][$this->_type];

            $css = $resources['css'];
            if (!empty($css)){

                $blockCss = $block['css'] ?? [];
                $css = (isset($allow['css']) && $allow['css'] !== [])? array_merge($css,$allow['css']) : $css;

                foreach ($css as $cssKey => $file){

                    if (in_array($cssKey, $blockCss, true))
                    {
                        continue;
                    }

                    if ($cssKey === 'fontawsom'){
                        $output .= "<link rel='stylesheet' href='$file' integrity='sha384-KA6wR/X5RY4zFAHpv/CnoG2UW1uogYfdnP67Uv7eULvTveboZJg0qUpmJZb5VqzN' crossorigin='anonymous' />";
                    }else{
                        $output .= "<link rel='stylesheet' href='$file' />";
                    }
                }
            }

            $js = $resources['js'];
            if (!empty($js)){
                $blockJs = $block['js'] ?? [];
                $js = (isset($allow['js']) && $allow['js'] !== [])? array_merge($js,$allow['js']) : $js;

                foreach ($js as $jsKey => $file){
                    if (in_array($jsKey, $blockJs, true))
                    {
                        continue;
                    }

                    $output .= "<script src='$file'></script>";
                }
            }

        }

        echo $output;

    }

    private function renderFooterResources($block,$allow){
        extract($this->_data);

        $output = '';
        if (!array_key_exists('footer', $this->_template_parts)){
            trigger_error('Sorry There Is No Header Resources Blocks In Your Project',E_USER_WARNING);
        }else{
            $js = $this->_template_parts['footer'][$this->_type]['js'];

            if (!empty($js)){
                $blockJs = $block['js'] ?? [];
                $js = (isset($allow['js']) && $allow['js'] !== [])? array_merge($js,$allow['js']) : $js;
                foreach ($js as $jsKey => $file){
                    if (in_array($jsKey, $blockJs, true))
                    {
                        continue;
                    }

                    $output .= "<script src='$file'></script>";
                }
            }

        }

        echo $output;
    }

    public function renderApp($block,$allow){

        $blockHeader = $block['header'] ?? [];
        $blockFooter = $block['footer'] ?? [];
        $blockBlocks = $block['blocks'] ?? [];

        $allowHeader = $allow['header'] ?? [];
        $allowFooter = $allow['footer'] ?? [];
        $allowBlocks = $allow['blocks'] ?? [];

        $this->renderTemplateHeaderStart();
        $this->renderHeaderResources($blockHeader,$allowHeader);
        $this->renderTemplateHeaderEnd();
        $this->renderTemplateBlocks($blockBlocks,$allowBlocks);
        $this->renderFooterResources($blockFooter,$allowFooter);
        $this->renderTemplateFooter();
    }

    public function getParts($for)
    {
        $header = $this->_template_parts['header'][$for] ?? [];
        $footer = $this->_template_parts['footer'][$for] ?? [];
        return ['header'=>$header,'footer'=>$footer];
    }
}
