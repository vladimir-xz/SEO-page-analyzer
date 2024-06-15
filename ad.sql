--
-- Структура таблицы "ad"
--

CREATE TABLE "ad" (
  "ad_id" bigint NOT NULL,
  "client_id" bigint DEFAULT NULL,
  "image_url" text,
  "pictures" text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы "ad"
--

INSERT INTO "ad" ("ad_id", "client_id", "image_url", "pictures") VALUES
(136586398, 15641551, NULL, NULL),
(157579069, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/07\\/8BCEDD.jpg\"]', NULL),
(157862071, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/07\\/8BCEDD.jpg\"]', NULL),
(157283075, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/07\\/8BCEDD.jpg\"]', NULL),
(157862072, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/07\\/8BCEDD.jpg\"]', NULL),
(157199166, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/07\\/8BCEDD.jpg\"]', NULL),
(157862070, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/07\\/8BCEDD.jpg\"]', NULL),
(157862143, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/07\\/8BCEDD.jpg\"]', NULL),
(157579066, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/07\\/8BCEDD.jpg\"]', NULL),
(157579067, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/07\\/8BCEDD.jpg\"]', NULL),
(157579068, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/07\\/8BCEDD.jpg\"]', NULL),
(156042348, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/1C\\/EE6355.jpg\"]', NULL),
(155829158, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/1C\\/EE6355.jpg\"]', NULL),
(155829130, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/1C\\/EE6355.jpg\"]', NULL),
(157283074, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/25\\/AFF5D1.jpg\"]', NULL),
(156803169, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/25\\/AFF5D1.jpg\"]', NULL),
(156803286, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/25\\/AFF5D1.jpg\"]', NULL),
(157199167, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/25\\/AFF5D1.jpg\"]', NULL),
(156959051, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/25\\/AFF5D1.jpg\"]', NULL),
(156959048, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/25\\/AFF5D1.jpg\"]', NULL),
(153602207, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/F3\\/21D7C7.png\"]', NULL),
(153602210, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/F3\\/21D7C7.png\"]', NULL),
(154041219, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/F3\\/21D7C7.png\"]', NULL),
(154041362, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/F3\\/21D7C7.png\"]', NULL),
(154992869, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/F3\\/21D7C7.png\"]', NULL),
(151028978, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/F3\\/21D7C7.png\"]', NULL),
(150967194, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/F3\\/21D7C7.png\"]', NULL),
(150967191, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/F3\\/21D7C7.png\"]', NULL),
(150967188, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/F3\\/21D7C7.png\"]', NULL),
(156803170, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/F4\\/D28A24.jpg\"]', NULL),
(156959049, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/F4\\/D28A24.jpg\"]', NULL),
(157283073, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/F4\\/D28A24.jpg\"]', NULL),
(153869604, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/FD\\/210195.png\"]', NULL),
(155316032, 15641551, '[\"https:\\/\\/r.mradx.net\\/img\\/FD\\/210195.png\"]', NULL);