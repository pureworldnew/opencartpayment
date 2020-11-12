#create table to hold data
DROP TABLE IF EXISTS oc_adv_report_attrib_mv;
CREATE TABLE oc_adv_report_attrib_mv(
	`attribute_title` VARCHAR(100),
	`attribute_name`  VARCHAR(100),
	`last_refresh` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


#create materialistic view refresh procedure
DROP PROCEDURE IF EXISTS refresh_adv_report_attrib;

DELIMITER $$
CREATE PROCEDURE refresh_adv_report_attrib (
    OUT rc INT
)
BEGIN

	TRUNCATE TABLE oc_adv_report_attrib_mv;
	INSERT INTO oc_adv_report_attrib_mv(attribute_title,attribute_name)
	SELECT DISTINCT LCASE(CONCAT(agd.name,'_',ad.name,'_',pa.text)) AS attribute_title, 
	CONCAT(agd.name,'  >  ',ad.name,'  >  ',pa.text) AS attribute_name 
	FROM `oc_product_attribute` pa, `oc_attribute_description` ad, `oc_attribute` a, `oc_attribute_group_description` agd 
	WHERE pa.language_id = '1' AND pa.attribute_id = ad.attribute_id AND ad.language_id = '1' 
	AND ad.attribute_id = a.attribute_id AND a.attribute_group_id = agd.attribute_group_id AND agd.language_id = '1' 
	GROUP BY agd.name, ad.name, pa.text ORDER BY agd.name, ad.name, pa.text ASC;

	SET rc = 0;
END;
$$
DELIMITER ;

#call procedure to fill report data
CALL refresh_adv_report_attrib(@rc);
