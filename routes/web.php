<?php
use App\Middleware\AuthClient;
use App\Middleware\NotAuth;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\ItemListController;
use App\Controllers\OfferController;
use App\Controllers\YouSnoozeController;
use App\Controllers\NewProductController;
use App\Controllers\OrderListController;
use App\Controllers\FavoriteController;
use App\Controllers\ItemIndexController;
use App\Controllers\BackOrderController;
use App\Controllers\InvoicesController;
use App\Controllers\DropShipController;
use App\Controllers\QuestionController;
use App\Controllers\InformationController;
use App\Controllers\RequestDigitalBillingController;
use App\Controllers\HelpController;
use App\Controllers\GeneralInformationController;

$app->get('/signout', LoginController::class . ':signout')->setName('signout');

//Admin
// $app->group('/vw-admin', function () {
// 	$this->get('/', AdminController::class . ':index')->setName('admin.home');

// 	$this->get('/dealers', AdminController::class . ':getDealers')->setName('admin.get.dealers');

// 	$this->get('/orders', AdminController::class . ':getOrders')->setName('admin.get.orders');

// 	$this->get('/edit-dealer/{id}', AdminController::class . ':getEditDealer')->setName('admin.edit.dealer');

// 	$this->get('/add-dealer', AdminController::class . ':getAddDealer')->setName('admin.get.add.dealer');

// 	$this->post('/post-add-dealer', AdminController::class . ':postAddDealer')->setName('admin.post.add.dealer');

// })->add(new AuthAdmin($container));

//Item list
$app->group('/item-list', function () {
	$this->get('/', ItemListController::class . ':index')->setName('get.item.list');

	$this->get('/api/getarticles', ItemListController::class . ':getArticles');

	$this->post('/api/getarticledetails', ItemListController::class . ':getArticleDetails');

	$this->post('/api/postaddtocart', ItemListController::class . ':postAddToCart');

	$this->post('/api/addtofavorite', FavoriteController::class . ':addToFavorite');
})->add(new AuthClient($container));

//Offer
$app->group('/offer', function () {
	$this->get('/', OfferController::class . ':index')->setName('get.offer');

	$this->get('/api/getarticles', OfferController::class . ':getArticles');

	$this->post('/api/getarticledetails', OfferController::class . ':getArticleDetails');

	$this->post('/api/postaddtocart', OfferController::class . ':postAddToCart');

	$this->post('/api/addtofavorite', FavoriteController::class . ':addToFavorite');
})->add(new AuthClient($container));

//You Snooze you loose
$app->group('/you-snooze-you-loose', function () {
	$this->get('/', YouSnoozeController::class . ':index')->setName('get.you.snooze');

	$this->get('/api/getarticles', YouSnoozeController::class . ':getArticles');

	$this->post('/api/getarticledetails', YouSnoozeController::class . ':getArticleDetails');

	$this->post('/api/postaddtocart', YouSnoozeController::class . ':postAddToCart');

	$this->post('/api/addtofavorite', FavoriteController::class . ':addToFavorite');
})->add(new AuthClient($container));

//New product
$app->group('/new', function () {
	$this->get('/', NewProductController::class . ':index')->setName('get.new.product');

	$this->get('/api/getarticles', NewProductController::class . ':getArticles');

	$this->post('/api/getarticledetails', NewProductController::class . ':getArticleDetails');

	$this->post('/api/postaddtocart', NewProductController::class . ':postAddToCart');

	$this->post('/api/addtofavorite', FavoriteController::class . ':addToFavorite');
})->add(new AuthClient($container));

//Order list
$app->group('/order-list', function () {
	$this->get('/', OrderListController::class . ':index')->setName('get.order.list');

	$this->post('/api/postaddtocart', OrderListController::class . ':postAddToCart');

	$this->get('/remove-from-cart/{id}', OrderListController::class . ':removeFromCart')->setName('get.remove.from.cart');
})->add(new AuthClient($container));

//Favorite
$app->group('/favorites', function () {
	$this->get('/', FavoriteController::class . ':index')->setName('get.favorites');

	$this->post('/api/getarticledetails', FavoriteController::class . ':getArticleDetails');

	$this->get('/api/getarticles', FavoriteController::class . ':getArticles');

	$this->post('/api/postaddtocart', FavoriteController::class . ':postAddToCart');

})->add(new AuthClient($container));

//Item index
$app->group('/item-index', function () {
	$this->get('/', ItemIndexController::class . ':index')->setName('get.item.index');
})->add(new AuthClient($container));

//Back orders
$app->group('/back-orders', function () {
	$this->get('/', BackOrderController::class . ':index')->setName('get.back.orders');
})->add(new AuthClient($container));

//Invoices
$app->group('/invoices', function () {
	$this->get('/', InvoicesController::class . ':index')->setName('get.invoices');
})->add(new AuthClient($container));

//Dropship
$app->group('/dropship', function () {
	$this->get('/', DropShipController::class . ':index')->setName('get.dropship');
})->add(new AuthClient($container));

//Questions
$app->group('/questions', function () {
	$this->get('/', QuestionController::class . ':index')->setName('get.ask.question');

	$this->post('/post-question', QuestionController::class . ':postQuestion')->setName('post.ask.question');
})->add(new AuthClient($container));

//Information
$app->group('/information', function () {
	$this->get('/newsletter', InformationController::class . ':index')->setName('get.newsletter');

	$this->get('/request-digital-billing', RequestDigitalBillingController::class . ':index')->setName('get.request.digital.billing');
});

//Help
$app->group('/help', function () {
	$this->get('/', HelpController::class . ':index')->setName('get.help');
});

//General information
$app->group('/general-information', function () {
	$this->get('/', GeneralInformationController::class . ':index')->setName('get.general.information');
});

$app->group('', function() {
	//admin signin
	$this->get('/vw-admin/auth', AdminController::class . ':getSignIn')->setName('admin.get.sign.in');

	$this->post('/vw-admin/post-signin', AdminController::class . ':postSignIn')->setName('admin.post.sign.in');

    $this->get('/', HomeController::class . ':index')->setName('index');

    $this->post('/post-signin', LoginController::class . ':postSignIn')->setName('post.signin');

    $this->get('/forgotten-password', LoginController::class . ':forgottenPassword')->setName('forgotten.password');

    $this->post('/post-forgotten-password', LoginController::class . ':postForgottenPassword')->setName('post.forgotten.password');

    $this->get('/reset-password/token/{token_id}', LoginController::class . ':resetPassword')->setName('reset.password');

    $this->post('/post-new-password', LoginController::class . ':postNewPassword')->setName('post.new.password');

})->add(new NotAuth($container));