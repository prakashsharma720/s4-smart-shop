ALTER TABLE `orders` ADD `address1` TEXT NOT NULL AFTER `referral_code`, ADD `address2` TEXT NULL AFTER `address1`, ADD `city` VARCHAR(150) NOT NULL AFTER `address2`, ADD `state` VARCHAR(100) NOT NULL AFTER `city`, ADD `pincode` INT(6) NOT NULL AFTER `state`;
ALTER TABLE `orders` ADD `payment_id` TEXT NULL AFTER `payment_status`;
ALTER TABLE `orders` ADD `referal_remark` TEXT NULL AFTER `payment_status`;
ALTER TABLE `orders` ADD `payment_method` VARCHAR(50) NOT NULL AFTER `payment_status`;

-- 30-10-2025
ALTER TABLE `users` ADD `placement_id` INT(10) NOT NULL AFTER `referal_code`, ADD `position` ENUM('left','right') NOT NULL AFTER `placement_id`, ADD `joining_date` DATE NULL DEFAULT NULL AFTER `position`;
