<?php
namespace Themes\GoTrip\Tour\Blocks;

use Modules\Media\Helpers\FileHelper;
use Modules\Template\Blocks\BaseBlock;

class Testimonial extends BaseBlock
{
    public function getOptions(){
        return [
            'settings' => [
                [
                    'id'        => 'title',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Title')
                ],
                [
                    'id'        => 'subtitle',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Subtitle')
                ],
                [
                    'id'        => 'happy_people_number',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Happy people number')
                ],
                [
                    'id'        => 'happy_people_text',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Happy people text')
                ],
                [
                    'id'        => 'overall_rating_number',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Overall rating number')
                ],
                [
                    'id'        => 'overall_rating_text',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Overall rating text')
                ],
                [
                    'id'        => 'overall_rating_star',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Overall rating star')
                ],
                [
                    'id'            => 'style',
                    'type'          => 'radios',
                    'label'         => __('Style Background'),
                    'values'        => [
                        [
                            'value'   => '',
                            'name' => __("Style 1")
                        ],
                        [
                            'value'   => 'style_2',
                            'name' => __("Style 2")
                        ],
                        [
                            'value'   => 'style_3',
                            'name' => __("Style 3")
                        ],
                        [
                            'value'   => 'style_4',
                            'name' => __("Style 4")
                        ],
                        [
                            'value'   => 'style_5',
                            'name' => __("Style 5")
                        ],
                        [
                            'value'   => 'style_6',
                            'name' => __("Style 6")
                        ],
                        [
                            'value'   => 'style_7',
                            'name' => __("Style 7")
                        ],
                    ]
                ],
                [
                    'id'          => 'list_item',
                    'type'        => 'listItem',
                    'label'       => __('List Item(s)'),
                    'title_field' => 'title',
                    'settings'    => [
                        [
                            'id'        => 'title',
                            'type'      => 'input',
                            'inputType' => 'text',
                            'label'     => __('Title')
                        ],
                        [
                            'id'        => 'name',
                            'type'      => 'input',
                            'inputType' => 'text',
                            'label'     => __('Name')
                        ],
                        [
                            'id'        => 'job',
                            'type'      => 'input',
                            'inputType' => 'text',
                            'label'     => __('Job')
                        ],
                        [
                            'id'    => 'desc',
                            'type'  => 'textArea',
                            'label' => __('Desc')
                        ],
                        [
                            'id'        => 'number_star',
                            'type'      => 'input',
                            'inputType' => 'number',
                            'label'     => __('Number star')
                        ],
                        [
                            'id'    => 'avatar',
                            'type'  => 'uploader',
                            'label' => __('Avatar Image')
                        ],
                    ]
                ],
                [
                    'id'        => 'title_trusted',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Title Trusted')
                ],
                [
                    'id'          => 'list_trusted',
                    'type'        => 'listItem',
                    'label'       => __('List Trusted(s)'),
                    'title_field' => 'title',
                    'settings'    => [
                        [
                            'id'    => 'avatar',
                            'type'  => 'uploader',
                            'label' => __('Logo Image')
                        ],
                    ]
                ]
            ],
            'category'=>__("Other Block")
        ];
    }

    public function getName()
    {
        return __('List Testimonial');
    }

    public function content($model = [])
    {
        if (empty($model['style'])) {
            $model['style'] = 'style_1';
        }
        return view('Tour::frontend.blocks.testimonial.index', $model);
    }

    public function contentAPI($model = []){
        if(!empty($model['list_item'])){
            foreach (  $model['list_item'] as &$item ){
                $item['avatar_url'] = FileHelper::url($item['avatar'], 'full');
            }
        }
        return $model;
    }
}
