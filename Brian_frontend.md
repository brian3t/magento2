cp -r lib/web/requirejs ./pub/static/frontend/Sm/maxshop/en_US/
cp -r lib/web/* ./pub/static/frontend/Sm/maxshop/en_US/

cp -r vendor/magento/theme-frontend-blank/Magento_Theme/web/js ./pub/static/frontend/Sm/maxshop/en_US/Magento_Theme/
mkdir -p ./pub/static/frontend/Sm/maxshop/en_US/Magento_Catalog
cp -r ./vendor/magento/module-catalog/view/frontend/web/js ./pub/static/frontend/Sm/maxshop/en_US/Magento_Catalog
mkdir -p ./pub/static/frontend/Sm/maxshop/en_US/Magento_Msrp
cp -r ./vendor/magento/module-msrp/view/base/web/js ./pub/static/frontend/Sm/maxshop/en_US/Magento_Msrp/
mkdir -p ./pub/static/frontend/Sm/maxshop/en_US/Sm_Maxshop/
cp -r app/design/frontend/Sm/maxshop/Sm_Maxshop/web/js ./pub/static/frontend/Sm/maxshop/en_US/Sm_Maxshop/
mkdir -p ./pub/static/frontend/Sm/maxshop/en_US/Magento_Ui/js/lib/
cp -r vendor/magento/module-ui/view/base/web/js/lib/* ./pub/static/frontend/Sm/maxshop/en_US/Magento_Ui/js/lib/
cp -r vendor/magento/module-ui/view/base/web/js/* ./pub/static/frontend/Sm/maxshop/en_US/Magento_Ui/js/
cp brian/yttheme.css ./pub/static/frontend/Sm/maxshop/en_US/css/
cp app/design/frontend/Sm/maxshop/web/css/css_hack.css ./pub/static/frontend/Sm/maxshop/en_US/css/
mkdir -p ./pub/static/frontend/Sm/maxshop/en_US/Magento_Swatches/
cp -r app/design/frontend/Sm/maxshop/Magento_Swatches/web/css ./pub/static/frontend/Sm/maxshop/en_US/Magento_Swatches/
