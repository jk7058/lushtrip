<?php
namespace Themes\GoTrip\Hotel\Blocks;

use Modules\Template\Blocks\BaseBlock;
use Modules\Hotel\Models\Hotel;
use Modules\Location\Models\Location;

class ListHotel extends \Modules\Hotel\Blocks\ListHotel
{

    public function getOptions()
    {
        return [
            'settings' => [
                [
                    'id'        => 'title',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Title')
                ],
                [
                    'id'        => 'desc',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Desc')
                ],
                [
                    'id'        => 'number',
                    'type'      => 'input',
                    'inputType' => 'number',
                    'label'     => __('Number Item'),
                    "default" => "5",
                ],
                [
                    'id'            => 'style',
                    'type'          => 'radios',
                    'label'         => __('Style'),
                    'values'        => [
                        [
                            'value'   => 'normal',
                            'name' => __("Normal")
                        ],
                        [
                            'value'   => 'carousel',
                            'name' => __("Slider Carousel")
                        ]
                    ]
                ],
                [
                    'id'      => 'location_ids',
                    'type'    => 'select2',
                    'label'   => __('Filter by Location'),
                    'select2' => [
                        'ajax'  => [
                            'url'      => route('location.admin.getForSelect2'),
                            'dataType' => 'json'
                        ],
                        'width' => '100%',
                        'multiple'    => "true",
                        'placeholder' => __('-- Select --')
                    ],
                    'pre_selected'=>route('location.admin.getForSelect2',['pre_selected'=>1])
                ],
                [
                    'id'            => 'order',
                    'type'          => 'radios',
                    'label'         => __('Order'),
                    'values'        => [
                        [
                            'value'   => 'id',
                            'name' => __("Date Create")
                        ],
                        [
                            'value'   => 'title',
                            'name' => __("Title")
                        ],
                    ],
                ],
                [
                    'id'            => 'order_by',
                    'type'          => 'radios',
                    'label'         => __('Order By'),
                    'values'        => [
                        [
                            'value'   => 'asc',
                            'name' => __("ASC")
                        ],
                        [
                            'value'   => 'desc',
                            'name' => __("DESC")
                        ],
                    ],
                    "selectOptions"=> [
                        'hideNoneSelectedText' => "true"
                    ]
                ],
                [
                    'type'=> "checkbox",
                    'label'=>__("Only featured items?"),
                    'id'=> "is_featured",
                    'default'=>true
                ],
                [
                    'id'           => 'custom_ids',
                    'type'         => 'select2',
                    'label'        => __('List by IDs'),
                    'select2'      => [
                        'ajax'        => [
                            'url'      => route('hotel.admin.getForSelect2'),
                            'dataType' => 'json'
                        ],
                        'width'       => '100%',
                        'multiple'    => "true",
                        'placeholder' => __('-- Select --')
                    ],
                    'pre_selected' => route('hotel.admin.getForSelect2', [
                        'pre_selected' => 1
                    ])
                ],
            ],
            'category'=>__("Service Hotel")
        ];
    }

    public function content($model = [])
    {
        $list = $this->query($model);
        $locations = ($model['location_ids']) ? $this->list_locations($model['location_ids']) : null;
        $data = [
            'rows'       => $list,
            'style_list' => $model['style'],
            'title'      => $model['title'],
            'desc'       => $model['desc'],
            'locations'  => $locations
        ];
        return view('Hotel::frontend.blocks.list-hotel.index', $data);
    }

    public function list_locations($location_ids){
        $locations = Location::whereIn('id',$location_ids)->where('status','publish');
        return $locations->get();
    }

    public function query($model){
        $model_hotel = Hotel::select("bravo_hotels.*")->with(['location','translations','hasWishList']);
        if(empty($model['order'])) $model['order'] = "id";
        if(empty($model['order_by'])) $model['order_by'] = "desc";
        if(empty($model['number'])) $model['number'] = 5;
        if (!empty($model['location_ids'])) {
            $locations = Location::whereIn('id', $model['location_ids'])->where("status","publish")->get();
            if(!empty($locations)){
                $model_hotel->join('bravo_locations', function ($join) use ($locations) {
                    $join->on('bravo_locations.id', '=', 'bravo_hotels.location_id');
                    foreach ($locations as $k => $location){
                        if ($k == 0){
                            $join->where(function ($q) use($location){
                                $q->where('bravo_locations._lft','>=',$location->_lft);
                                $q->where('bravo_locations._rgt','<=',$location->_rgt);
                            });
                        } else {
                            $join->orWhere(function ($q) use($location){
                                $q->where('bravo_locations._lft','>=',$location->_lft);
                                $q->where('bravo_locations._rgt','<=',$location->_rgt);
                            });
                        }
                    }
                });
            }
        }
        if(!empty($model['is_featured']))
        {
            $model_hotel->where('bravo_hotels.is_featured',1);
        }
        if (!empty($model['custom_ids'])) {
            $model_hotel->whereIn("bravo_hotels.id", $model['custom_ids']);
        }
        $model_hotel->orderBy("bravo_hotels.".$model['order'], $model['order_by']);
        $model_hotel->where("bravo_hotels.status", "publish");
        $model_hotel->with('location');
        $model_hotel->groupBy("bravo_hotels.id");
        return $model_hotel->limit($model['number'])->get();
    }
}
