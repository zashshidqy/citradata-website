USE `citradata_db`;

CREATE TABLE IF NOT EXISTS `hero_slides` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `image_path`  VARCHAR(500) NOT NULL,
    `alt_text`    VARCHAR(255) DEFAULT NULL,
    `sort_order`  INT          NOT NULL DEFAULT 0,
    `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed with existing images
INSERT INTO `hero_slides` (`image_path`, `alt_text`, `sort_order`, `is_active`) VALUES
('assets/images/1.png', 'Slide 1', 1, 1),
('assets/images/2.png', 'Slide 2', 2, 1),
('assets/images/3.png', 'Slide 3', 3, 1),
('assets/images/4.png', 'Slide 4', 4, 1),
('assets/images/5.png', 'Slide 5', 5, 1),
('assets/images/6.png', 'Slide 6', 6, 1);
