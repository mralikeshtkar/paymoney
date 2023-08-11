-- Password Reset
ALTER TABLE `password_resets` ADD `code` VARCHAR(10) NULL AFTER `email`;

-- Email Templates
INSERT INTO `email_templates` (`language_id`, `temp_id`, `subject`, `body`, `lang`, `type`) VALUES ('1', '47', 'Notice for Password Reset!', 'Hi {user},\r\n <br><br> \r\nYou recently requested a password reset for your account. Please use the following code to reset your password: <br><br> {password_reset_code}\r\n\r\n <br><br> If you did not make this request, please contact our support team immediately. <br><br> \r\n\r\n Regards, <br><br> \r\n <b>{soft_name}</b>', 'en', 'email'), ('2', '47', '', '', 'ar', 'email'), ('3', '47', '', '', 'fr', 'email'), ('4', '47', '', '', 'pt', 'email'), ('5', '47', '', '', 'ru', 'email'), ('6', '47', '', '', 'es', 'email'), ('7', '47', '', '', 'tr', 'email'), ('8', '47', '', '', 'ch', 'email');




