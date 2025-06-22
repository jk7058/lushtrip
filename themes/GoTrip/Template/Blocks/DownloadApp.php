<?php
namespace Themes\Gotrip\Template\Blocks;

use Modules\Media\Helpers\FileHelper;
use Modules\Template\Blocks\BaseBlock;


class DownloadApp extends BaseBlock
{
    function getOptions()
    {
        $list_service = [];


        $arg[] = [
            'id'        => 'title',
            'type'      => 'input',
            'inputType' => 'text',
            'label'     => __('Title')
        ];

        $arg[] = [
            'id'        => 'sub_title',
            'type'      => 'input',
            'inputType' => 'text',
            'label'     => __('Sub Title')
        ];
        $arg[] = [
            'id'        => 'link_ios',
            'type'      => 'input',
            'inputType' => 'text',
            'label'     => __('Link Download Ios')
        ];

        $arg[] = [
            'id'        => 'link_android',
            'type'      => 'input',
            'inputType' => 'text',
            'label'     => __('Link Download Android')
        ];

        $arg[] = [
            'id'    => 'bg_image',
            'type'  => 'uploader',
            'label' => __('Background Image Uploader'),
        ];


        return ([
            'settings' => $arg,
            'category'=>__("Other Block")
        ]);
    }

    public function getName()
    {
        return __('Download App');
    }

    public function content($model = [])
    {
        $data = [];
        if (!empty($model['bg_image'])) {
            $data['bg_image_url'] = FileHelper::url($model['bg_image'], 'full');
        }
        $data = array_merge($model, $data);
        return $this->view('Template::frontend.blocks.download.index', $data);
    }

    public function contentAPI($model = []){
        return $model;
    }
}
