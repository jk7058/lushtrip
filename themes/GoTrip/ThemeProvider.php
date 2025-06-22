<?php
namespace Themes\GoTrip;

use Illuminate\Contracts\Http\Kernel;
use Themes\GoTrip\Hotel\Blocks\FormSearchHotel;
use Themes\GoTrip\Hotel\Blocks\ListHotel;
use Themes\GoTrip\Location\Blocks\ListLocations;
use Themes\GoTrip\News\Blocks\ListNews;
use Themes\Gotrip\Template\Blocks\DownloadApp;
use Themes\GoTrip\Template\Blocks\FormSearchAllService;
use Themes\Gotrip\Template\Blocks\ListAllService;
use Themes\Gotrip\Template\Blocks\Subscribe;
use Themes\GoTrip\Tour\Blocks\CallToAction;
use Themes\GoTrip\Tour\Blocks\ListFeaturedItem;
use Themes\GoTrip\Tour\Blocks\Testimonial;
use Themes\Gotrip\Template\Blocks\LoginRegister;

class ThemeProvider extends \Themes\Base\ThemeProvider
{

    public static $version = '1.0.0';
    public static $name = 'Go Trip';
    public static $parent = 'BC';
    public static function info()
    {
        // TODO: Implement info() method.
    }

    public function boot(Kernel $kernel)
    {

        parent::boot($kernel);
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
        //Hook Settings

    }

    public static function getTemplateBlocks(){
        return [
            'testimonial'=>Testimonial::class,
            'form_search_hotel'=>FormSearchHotel::class,
            "list_all_service"=>ListAllService::class,
            'list_locations'=>ListLocations::class,
            'call_to_action'=>CallToAction::class,
            'list_featured_item'=>ListFeaturedItem::class,
            'list_news'=>ListNews::class,
            'subscribe'=>Subscribe::class,
			'download_app' => DownloadApp::class,
			'login_register' => LoginRegister::class,
            'list_hotel'=>ListHotel::class
        ];
    }

    public function register()
    {
        parent::register();
        $this->app->register(UpdaterProvider::class);
    }

}
