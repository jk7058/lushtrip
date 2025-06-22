<?php
namespace Themes\Gotrip\Template\Blocks;

use Modules\Media\Helpers\FileHelper;
use Modules\Template\Blocks\BaseBlock;


class LoginRegister extends BaseBlock
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

        return ([
            'settings' => $arg,
            'category'=>__("Other Block")
        ]);
    }

    public function getName()
    {
        return __('Login Register');
    }

    public function content($model = [])
    {
        return $this->view('Template::frontend.blocks.login-register.index', $model);
    }

    public function contentAPI($model = []){
        return $model;
    }
}
