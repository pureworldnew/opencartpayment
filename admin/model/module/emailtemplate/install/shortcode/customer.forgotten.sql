INSERT INTO `oc_emailtemplate_shortcode` (`emailtemplate_shortcode_code`, `emailtemplate_shortcode_type`, `emailtemplate_shortcode_example`, `emailtemplate_id`) VALUES
('email', 'auto', 'user@oc.local', {_ID}),
('password', 'auto', '0b66103835', {_ID}),
('account_login', 'auto', 'http://oc.local/index.php?route=account/login&amp;email=user@oc.local', {_ID}),
('account_login_tracking', 'auto', 'http://oc.local/index.php?route=account/login&amp;email=user@oc.local', {_ID});