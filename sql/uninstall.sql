TRUNCATE TABLE `mc_slideshow_content`;
DROP TABLE `mc_slideshow_content`;
TRUNCATE TABLE `mc_slideshow`;
DROP TABLE `mc_slideshow`;

DELETE FROM `mc_config_img` WHERE `module_img` = 'slideshow' AND `attribute_img` = 'slideshow';

DELETE FROM `mc_admin_access` WHERE `id_module` IN (
    SELECT `id_module` FROM `mc_module` as m WHERE m.name = 'slideshow'
);