ALTER TABLE `orders` ADD `address1` TEXT NOT NULL AFTER `referral_code`, ADD `address2` TEXT NULL AFTER `address1`, ADD `city` VARCHAR(150) NOT NULL AFTER `address2`, ADD `state` VARCHAR(100) NOT NULL AFTER `city`, ADD `pincode` INT(6) NOT NULL AFTER `state`;
ALTER TABLE `orders` ADD `payment_id` TEXT NULL AFTER `payment_status`;
ALTER TABLE `orders` ADD `referal_remark` TEXT NULL AFTER `payment_status`;
ALTER TABLE `orders` ADD `payment_method` VARCHAR(50) NOT NULL AFTER `payment_status`;
