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
use App\Controllers\TeamVerwimpController;
use App\Controllers\CustomerInformationController;
use App\Controllers\RoundOffController;
use App\Controllers\ShopInformationController;
use App\Controllers\ReturnWarrantyController;
use App\Controllers\LoginCodesController;
use App\Controllers\OrderHistoryController;
use App\Controllers\LabelDimensionController;
use App\Controllers\ReturnWarrantyHistoryController;
use App\Controllers\VatMarginController;
use App\Controllers\ProfitMarginController;
use App\Controllers\ConfigurationController;

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

	$this->get('/{item_number}', OrderListController::class . ':removeFromOrder');

	$this->get('/{item_number}/{qty}', OrderListController::class . ':updateCartFromOrder');

	$this->post('/api/postaddtocart', OrderListController::class . ':postAddToCart');

	$this->get('/remove-from-cart/{id}', OrderListController::class . ':removeFromCart')->setName('get.remove.from.cart');
})->add(new AuthClient($container));

//Favorite
$app->group('/favorites', function () {
	$this->get('/', FavoriteController::class . ':index')->setName('get.favorites');

	$this->post('/api/getarticledetails', FavoriteController::class . ':getArticleDetails');

	$this->get('/api/getarticles', FavoriteController::class . ':getArticles');

	$this->post('/api/postaddtocart', FavoriteController::class . ':postAddToCart');

	$this->get('/remove-from-favorite/{id}', FavoriteController::class . ':removeFromFavorite')->setName('remove.from.favorite');

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

});

//Team Verwimp
$app->group('/team-verwimp', function () {
	$this->get('/', TeamVerwimpController::class . ':index')->setName('get.team.verwimp');
});

//Digital billing
$app->group('/request-digital-billing', function () {
	$this->get('/', RequestDigitalBillingController::class . ':index')->setName('get.request.digital.billing');

	$this->post('/post-digital-billing', RequestDigitalBillingController::class . ':postDigitalBilling')->setName('post.request.digital.billing');
});

//Help
$app->group('/help', function () {
	$this->get('/', HelpController::class . ':index')->setName('get.help');
});

//General information
$app->group('/general-information', function () {
	$this->get('/', GeneralInformationController::class . ':index')->setName('get.general.information');
});

//Customer information
$app->group('/customer-information', function () {
	$this->get('/', CustomerInformationController::class . ':index')->setName('get.customer.information');

	$this->post('/update-deliver-email', CustomerInformationController::class . ':updateCustomerDeliveryEmail')->setName('update.customer.delivery.email');

	$this->post('/update-invoice-email', CustomerInformationController::class . ':updateCustomerInvoiceEmail')->setName('update.customer.invoice.email');

	$this->post('/update-customer-backorder', CustomerInformationController::class . ':updateCustomerBackorder')->setName('update.customer.backorder');

	$this->post('/update-opening-hours', CustomerInformationController::class . ':updateOpeningHours')->setName('update.opening.hours');
});

//Label Dimension
$app->group('/label-dimension', function () {
	$this->get('/', LabelDimensionController::class . ':index')->setName('get.label.dimension');

	$this->get('/add-dimension', LabelDimensionController::class . ':addDimension')->setName('get.add.dimension');

	$this->post('/post-label-dimension', LabelDimensionController::class . ':postLabelDimension')->setName('post.label.dimension');

	$this->get('/edit-label-dimension/{id}', LabelDimensionController::class . ':editLabelDimension')->setName('edit.label.dimension');

	$this->get('/delete-label-dimension/{id}', LabelDimensionController::class . ':deleteLabelDimension')->setName('delete.label.dimension');

	$this->post('/update-label-dimension', LabelDimensionController::class . ':updateLabelDimension')->setName('update.label.dimension');
});

//Round off
$app->group('/round-off', function () {
	$this->get('/', RoundOffController::class . ':index')->setName('get.round.off');

	$this->get('/delete-round-off/{id}', RoundOffController::class . ':deleteRoundOff')->setName('delete.round.off');

	$this->get('/edit-round-off/{id}', RoundOffController::class . ':editRoundOff')->setName('edit.round.off');

	$this->post('/update-round-off', RoundOffController::class . ':updateRoundOff')->setName('update.round.off');

	$this->post('/post-new-round-off', RoundOffController::class . ':postNewRoundOff')->setName('post.new.round.off');
});

//Shop information
$app->group('/shop-information', function () {
	$this->get('/', ShopInformationController::class . ':index')->setName('get.shop.information');

	$this->post('/post-shop-information', ShopInformationController::class . ':postShopInformation')->setName('post.shop.information');
});


//Return warranty
$app->group('/return-warranty', function () {
	$this->get('/', ReturnWarrantyController::class . ':index')->setName('get.return.warranty');
});

//Login codes
$app->group('/login-codes', function () {
	$this->get('/', LoginCodesController::class . ':index')->setName('get.login.codes');

	$this->post('/post-login-codes', LoginCodesController::class . ':postLoginCodes')->setName('post.login.codes');

	$this->get('/delete-login-code/{id}', LoginCodesController::class . ':deleteLoginCode')->setName('delete.login.code');

	$this->get('/edit-login-code/{id}', LoginCodesController::class . ':editLoginCode')->setName('edit.login.code');

	$this->post('/update-login-code', LoginCodesController::class . ':updateLoginCode')->setName('update.login.code');
});

//VAT margin
$app->group('/vat-margins', function () {
	$this->get('/', VatMarginController::class . ':index')->setName('get.vat.margin');

	$this->post('/post-vat-margin', VatMarginController::class . ':postVatMargin')->setName('post.vat.margin');
});

//Profit margin
$app->group('/profit-margins', function () {
	$this->get('/', ProfitMarginController::class . ':index')->setName('get.profit.margin');
});

//Configuration
$app->group('/configuration', function () {
	$this->get('/', ConfigurationController::class . ':index')->setName('get.configuration');
});

//Orders history
$app->group('/orders-history', function () {
	$this->get('/', OrderHistoryController::class . ':index')->setName('get.orders.history');

	$this->get('/api/getorderitems/{id}', OrderHistoryController::class . ':getOrderItems');
});

//Return warranty history (bikes)
$app->group('/return-warranty-history', function () {
	$this->get('/', ReturnWarrantyHistoryController::class . ':index')->setName('get.return.warranty.history');

	$this->get('/api/getreturnwarrantyhistory/{id}', ReturnWarrantyHistoryController::class . ':getReturnWarrantyHistoryItems');
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