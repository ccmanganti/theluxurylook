<?php
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class,'home'])->name('home');
Route::get('/robots.txt',[SiteController::class,'robots']);
Route::get('/sitemap.xml',[SiteController::class,'sitemap']);
Route::get('/services',[SiteController::class,'services'])->name('services');
Route::get('/services/{slug}',[SiteController::class,'service'])->where('slug','[a-z0-9-]+')->name('service.show');
Route::get('/portfolio',[SiteController::class,'portfolioIndex'])->name('portfolio.index');
Route::get('/portfolio/{slug}',[SiteController::class,'category'])->where('slug','[a-z0-9-]+')->name('category.show');
Route::get('/portfolios/{slug}',[SiteController::class,'project'])->where('slug','[a-z0-9-]+')->name('project.show');
Route::get('/blog',[SiteController::class,'blog'])->name('blog');
Route::get('/blog/{slug}',[SiteController::class,'post'])->where('slug','[a-z0-9-]+')->name('post.show');
Route::get('/contact',[SiteController::class,'contact'])->name('contact');
Route::post('/contact',[SiteController::class,'submitContact'])->middleware('throttle:5,1')->name('contact.submit');
Route::get('/search',[SiteController::class,'search'])->name('search');
Route::get('/{path}',[SiteController::class,'page'])->where('path','about|the-studio|awards|business-planning-workshops|digital-products|yacht-interior-design-outfitting|the-luxury-look-business-planning-workshop');
Route::get('/{path}',[SiteController::class,'special'])->where('path','checkout|paypal-checkout|order-confirmation');
Route::fallback([SiteController::class,'fallback']);
