TRUNCATE TABLE `mc_slideshow_content`;
DROP TABLE `mc_slideshow_content`;
TRUNCATE TABLE `mc_slideshow`;
DROP TABLE `mc_slideshow`;
TRUNCATE TABLE `mc_slideshow_category_content`;
DROP TABLE `mc_slideshow_category_content`;
TRUNCATE TABLE `mc_slideshow_category`;
DROP TABLE `mc_slideshow_category`;
TRUNCATE TABLE `mc_slideshow_pages_content`;
DROP TABLE `mc_slideshow_pages_content`;
TRUNCATE TABLE `mc_slideshow_pages`;
DROP TABLE `mc_slideshow_pages`;

DELETE FROM `mc_config_img` WHERE `module_img` = 'plugins' AND `attribute_img` = 'slideshow';

DELETE FROM `mc_admin_access` WHERE `id_module` IN (
    SELECT `id_module` FROM `mc_module` as m WHERE m.name = 'slideshow'
);