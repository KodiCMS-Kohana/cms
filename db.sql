-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 05, 2012 at 07:40 PM
-- Server version: 5.5.25
-- PHP Version: 5.3.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `flexo`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache_page`
--

CREATE TABLE `cache_page` (
  `page_id` int(11) NOT NULL,
  `cache_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `page_id` (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dishes`
--

CREATE TABLE `dishes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `weight` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `position` (`position`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=166 ;

--
-- Dumping data for table `dishes`
--

INSERT INTO `dishes` (`id`, `position`, `name`, `type_id`, `category_id`, `description`, `weight`, `price`, `image`) VALUES
(93, 1, 'Цезарь с курицей', 1, 1, 'салат романо, грудка куриная, помидоры бакинские,\r\nбекон, гренки чесночные, сыр пармезан, соус Цезарь', '1/240', 550, ''),
(94, 1, 'Цезарь с креветками', 1, 1, 'салат романо, креветки тигровые, помидоры \r\nбакинские, бекон с/к, гренки чесночные, сыр пармезан,\r\nсоус Цезарь', '1/250', 700, ''),
(95, 2, 'Салат из свежего щавеля', 1, 1, 'томаты вяленые, салат айсберг, щавель свежий, \r\nпечень куриная, баклажаны жареные, соус Тайский, \r\nлук резанец, перец чили острый', '1/250', 600, ''),
(96, 3, 'Салат с морепродуктами', 1, 1, ' салат латук, руккола, фрессе, помидоры бакинские,\r\nсоус Тайский, кальмар, осьминог мини, креветки \r\nтигровые, мидии гигант-киви', '1/250', 800, ''),
(97, 4, 'Салат из баклажанов с сыром Филадельфия', 1, 1, 'баклажан жареный, фета, помидоры вяленые, \r\nпомидоры конкосе, базиликовая заправка, руккола,\r\nмасло оливковое, сок лимона', '1/260', 500, ''),
(98, 6, 'Салат с утиной грудкой', 1, 1, 'грудка утиная Магре, руккола, латук, фрессе, ананас\r\nсвежий, соус Маракуя', '1/200', 700, ''),
(99, 5, 'Салат Капрезе', 1, 1, 'помидоры бакинские, сыр Мацарелла, базиликовая\r\nзаправка, базилик свежий', '1/330', 850, ''),
(100, 2, 'Тар-тар из тунца', 2, 1, 'огурцы свежие люкс, каперсы, лук шалот, филе тунца,\r\nруккола люкс, перец болгарский, бальзамический уксус\r\nвыпаренный', '1/180', 750, ''),
(101, 3, 'Карпачо из мраморной говядины', 2, 1, 'говяжья мраморка, руккола, пармезан, перец чили острый,\r\nсоус Азия', '1/100', 900, ''),
(102, 4, 'Сырное ассорти', 2, 1, 'сыр Таледжио, камамбер, дор-блю, сыр скаморца, \r\nПекарино-романо, орехи грецкие, мед цветочный, виноград', '1/380', 1200, ''),
(103, 5, 'Рыбное ассорти', 2, 1, '', '1/380', 1700, ''),
(104, 0, 'Пармская ветчина с дыней', 2, 1, 'окорок типика, дыня медовая, мята перечная', '1/200', 650, ''),
(105, 6, 'Тарелка итальянских деликатесов', 2, 1, 'салями, бризаола, парма, гренки чесночные, сырный соус\r\n«Blue cheese», базилик свежий зеленый', '1/300', 1200, ''),
(106, 7, 'Брускета', 2, 1, 'с овощами и сыром пармезан', '1/120', 400, ''),
(107, 8, 'Брускета', 2, 1, 'с баклажанами', '1/110', 400, ''),
(108, 9, 'Брускета', 2, 1, 'с с/с лососем', '1/110', 400, ''),
(109, 1, 'Брускета', 2, 1, 'с пармой', '1/110', 400, ''),
(110, 2, 'Морские Королевы', 3, 1, 'креветки тигровые, кляр, ананас свежий, лимон,\r\nклассический соус Цезарь', '170/145', 750, ''),
(111, 3, 'Тунец по-креольски', 3, 1, 'филе тунца, спаржа свежая зеленая, кунжут белый, \r\nруккола, лимон свежий, соус Тирияк', '1/360', 1000, ''),
(112, 4, 'Фуа-гра на горячих тостах', 3, 1, 'утиная печень Фуа-гра, хлеб тостовый, соус черничный,\r\nмалина свежая, мята', '1/130', 1200, ''),
(113, 5, 'Жареный сыр с ягодным соусом', 3, 1, 'сыр Эдам, панировка, мята, клубника свежая, ягодный \r\nсоус', '1/235', 1000, ''),
(114, 6, 'Паста Сальмоне', 5, 1, 'паста фетучини, лосось, сливки 38%, сыр пармезан, \r\nикра красная, базилик свежий зеленый', '1/335', 780, ''),
(115, 7, 'Паста Карбонара', 5, 1, 'ветчина, бекон с/к, валеные помидоры, паста спагетти, \r\nсливки 38%, помидоры черри, желток куриный, сыр \r\nпармезан, базилик свежий', '1/320', 750, ''),
(116, 8, 'Паста Дель-Мара', 5, 1, 'мидии гигант-киви, креветки тигровые, соус сальса, \r\nсливки 38 %, спагетти де Чеко, сыр Пармезан', '1/470', 1100, ''),
(117, 10, 'Паста Милано', 5, 1, 'горошек зеленый, цуккини, грудка куриная, сливки, \r\nпаста фетучини, сыр пармезан, базилик зеленый', '1/290', 650, ''),
(118, 11, 'Борщ', 4, 1, '', '350/30', 420, ''),
(119, 12, 'Суп щавелевый', 4, 1, '', '300/20/20', 280, ''),
(120, 13, 'Суп пюре грибной', 4, 1, '', '1/210', 350, ''),
(164, 14, 'Суп сырный с морепродуктами', 4, 1, '', '1/330', 420, ''),
(122, 15, 'Суп – гуляш «Венгерский»', 4, 1, '', '1/315', 450, ''),
(123, 16, 'Суп томатный', 4, 1, '', '1/315', 420, ''),
(124, 17, 'Корейка ягненка', 7, 1, 'корейка ягненка, соус Сальса, лук  красный \r\nмаринованный, салат микс, помидор бакинский', '230/100', 1400, ''),
(125, 9, 'Стейк Рибай', 7, 1, 'рибай прайм, аджика домашняя, соус перечный, \r\nсалат микс, помидоры бакинские', '220/120', 1400, ''),
(126, 18, 'Стейк Миньон', 7, 1, 'мраморная говядина, соус Сальса, соус перечный, \r\nсалат микс, помидоры бакинские', '210/120', 1800, ''),
(127, 19, 'Медальоны из мраморной говядины с соусом из сморчков', 7, 1, 'говядина мраморная, соус из сморчков, помидоры черри, \r\nгрибы шампиньоны, салат микс', '1/400', 2000, ''),
(128, 1, 'Утиная грудка «Магре» с яблоками', 7, 1, 'грудка утиная, яблоко греми-смит, соус клубничный, \r\nсоус черничный, мята', '1/400', 1600, ''),
(129, 2, 'Картофельное пюре с сыром', 9, 1, '', '1/150', 200, ''),
(130, 3, 'Шпинат припущенный', 9, 1, '', '1/90', 220, ''),
(131, 4, 'Рис микс', 9, 1, '', '1/100', 150, ''),
(132, 5, 'Овощи гриль', 9, 1, '', '1/200', 350, ''),
(133, 6, 'Мини-картофель с розмарином', 9, 1, '', '1/150', 220, ''),
(134, 7, 'Спаржа обжаренная', 9, 1, '', '1/100', 300, ''),
(135, 9, 'Картофель фри', 9, 1, '', '1/145', 250, ''),
(136, 10, 'Стейк из тунца', 8, 1, 'филе тунца, дайкон, морковь  свежая, грибы  Ши-таки, \r\nсоус Тирияки', '1/265', 1000, ''),
(137, 11, 'Дорадо на гриле, на пару', 8, 1, 'дорадо, соус из сморчков, базиликовая заправка, лимон,\r\nсалат микс, помидоры бакинские', '420/50', 1100, ''),
(138, 12, 'Филе лосося жаренного на гриле', 8, 1, 'филе лосося, соус из сморчков, заправка базиликовая,\r\nсалат микс, долька лимона', '1/290', 900, ''),
(139, 13, 'Филе черной трески под мисо соусом', 8, 1, 'филе черной трески, мисо соус, спаржа зеленая, \r\nперец болгарский, помидоры бакинские', '1/280', 1600, ''),
(140, 14, 'Сибас на гриле', 8, 1, 'сибас, соус из сморчков, базиликовая заправка, салат\r\nмикс, долька лимона, помидор бакинский', '420/50', 1100, ''),
(141, 15, 'Карпачо из ананаса', 10, 1, '', '1/110', 600, ''),
(142, 16, 'Штрудель творожный', 10, 1, '', '1/250', 600, ''),
(143, 17, 'Теплые яблоки с мороженым', 10, 1, '', '1/280', 550, ''),
(144, 18, 'Птифуры', 10, 1, '', '1/50', 120, ''),
(145, 19, 'Тирамису', 10, 1, '', '1/185', 500, ''),
(146, 8, 'Чизкейк', 10, 1, '', '1/220', 450, ''),
(147, 0, 'Вишневый штрудель', 10, 1, '', '1/200', 500, ''),
(165, 0, 'Мильфей', 10, 1, '', '1/155', 600, ''),
(149, 0, 'Фруктовая ваза', 11, 1, '', '1кг.', 1500, ''),
(150, 0, 'Фруктовая ваза', 11, 1, '', '2кг.', 2500, ''),
(151, 0, 'Клубника', 12, 1, '', '100', 500, ''),
(152, 0, 'Ежевика', 12, 1, '', '100', 680, ''),
(153, 0, 'Малина', 12, 1, '', '100', 900, ''),
(154, 0, 'Голубика', 12, 1, '', '100', 700, ''),
(155, 0, 'Красная смородина', 12, 1, '', '100', 700, ''),
(156, 0, 'Клубничное мороженое', 13, 1, '', '50', 250, ''),
(157, 0, 'Шоколадное мороженое', 13, 1, '', '50', 250, ''),
(158, 0, 'Фисташковое мороженое', 13, 1, '', '50', 250, ''),
(159, 0, 'Ванильное мороженое', 13, 1, '', '50', 250, ''),
(160, 0, 'Сорбет черная смородина', 13, 1, '', '50', 260, ''),
(161, 0, 'Сорбет манго', 13, 1, '', '50', 260, ''),
(162, 0, 'Сорбет лимон- лайм', 13, 1, '', '50', 260, ''),
(163, 0, 'Собрет малина', 13, 1, '', '50', 260, '');

-- --------------------------------------------------------

--
-- Table structure for table `dish_categories`
--

CREATE TABLE `dish_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `position` (`position`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `dish_categories`
--

INSERT INTO `dish_categories` (`id`, `position`, `name`) VALUES
(1, 2, 'Fusion'),
(5, 1, 'Fresh menu'),
(6, 0, 'Bar');

-- --------------------------------------------------------

--
-- Table structure for table `dish_types`
--

CREATE TABLE `dish_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `position` (`position`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `dish_types`
--

INSERT INTO `dish_types` (`id`, `position`, `name`) VALUES
(1, 3, 'Салаты'),
(2, 0, 'Холодные закуски'),
(3, 1, 'Горячие закуски'),
(4, 2, 'Супы'),
(5, 4, 'Паста'),
(6, 5, 'Японская кухня'),
(7, 7, 'Горячие блюда из мяса'),
(8, 8, 'Горячие блюда из рыбы'),
(9, 9, 'Гарниры'),
(10, 23, 'Десерты'),
(11, 10, 'Фрукты'),
(12, 11, 'Ягоды'),
(13, 12, 'Мороженное / Сорбеты'),
(14, 13, 'Спец предложение от Шеф-повара. Летнее меню'),
(15, 14, 'Игристые вина'),
(16, 15, 'Вино белое'),
(17, 16, 'Вино по бокалам'),
(18, 17, 'Коньяк'),
(19, 18, 'Виски'),
(20, 19, 'Водка'),
(21, 21, 'Текила'),
(22, 22, 'Шампанское'),
(23, 6, 'Вино красное'),
(24, 24, 'Вино розовое'),
(25, 26, 'Аперетив'),
(26, 27, 'Дижестив'),
(27, 28, 'Ром'),
(28, 29, 'Ликер'),
(29, 30, 'Пиво'),
(30, 31, 'Безалкогольные напитки'),
(31, 32, 'Горячие напитки'),
(32, 33, 'Сигары'),
(33, 25, 'Джин'),
(34, 34, 'Сок свежевыжатый'),
(35, 20, 'Cок');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `top` int(1) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL DEFAULT '',
  `presenter` varchar(255) NOT NULL DEFAULT '',
  `date` date NOT NULL,
  `time` varchar(5) NOT NULL DEFAULT '12:00',
  `descr_short` text,
  `descr_long` text,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `top`, `title`, `location`, `presenter`, `date`, `time`, `descr_short`, `descr_long`, `image`) VALUES
(4, 0, '1-й учебно-практический курс', 'Можайское шоссе, д.2', 'профессор С.П.Сысолятин', '2011-04-28', '12:00', 'Курс для врачей хирургов-стоматологов и челюстно-лицевых хирургов.', '<h2 class="title"><a style="visibility: visible;" href="/index.php/programmy/25-1-yj-uchebno-prakticheskij-kurs"><span style="color: #000acc;"><span>1-ый</span> учебно-практический курс</span></a></h2><p>&nbsp;</p><p><span style="font-size: small;"><span style="background-color: #ffffff;"><span style="color: #000000;"><span style="color: #0000ff;"><span style="background-color: #000000;"><span style="font-size: large; color: #0000ff; background-color: #ffffff;">Центр эндоскопической стоматологии и челюстно-лицевой хирургии</span></span></span></span></span></span></p><p style="text-align: center;"><span style="font-size: small;"><span style="background-color: #ffffff;"><span style="color: #000000;"><span style="color: #0000ff;"><span style="font-size: medium;"><span style="background-color: #000000;"><span style="font-size: large; color: #0000ff; background-color: #ffffff;">&laquo;Учебный центр ассоциации медицинских и фармацевтических ВУЗов&raquo;</span></span> </span></span><br /></span></span></span></p><p style="text-align: center;"><span style="font-size: small;"><span style="font-size: medium;">при технической поддержке фирмы Кarl Storz GmbH &amp; Co. KG<br />и компании Dr.Reddys</span></span></p><p style="text-align: center;">&nbsp;</p><p>&nbsp;</p><table border="0"><tbody><tr><td><img title="logo_storz_small.jpg" src="/public/media/news/logo_storz_small.jpg" alt="logo_storz_small.jpg" width="188" height="64" /></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><img title="logo_dr.jpg" src="/public/media/news/logo_dr.jpg" alt="logo_dr.jpg" width="193" height="64" /></td></tr></tbody></table><h2 style="visibility: visible; text-align: center;">&nbsp;</h2><h2 style="visibility: visible; text-align: center;"><span>&laquo;ОТКРЫТАЯ</span>&nbsp;И ЭНДОСКОПИЧЕСКАЯ ХИРУРГИЯ ОДОНТОГЕННОГО ВЕРХНЕЧЕЛЮСТНОГО СИНУСИТА&raquo; <br />28-29 апреля, 2011 Москва</h2><p><strong>Дорогие коллеги!</strong></p><p>Мы с радостью сообщаем Вам о проведении первого курса посвященного современным технологиям диагностики и лечения одонтогенного верхнечелюстного синусита.</p><p><strong>Курс разработан для практических врачей специализирующихся в:</strong></p><ul><li>хирургической стоматологии;</li><li>челюстно-лицевой хирургии.</li></ul><p><strong>Лекционный курс включает в себя:</strong></p><ul><li>базовые вопросы анатомии и физиологии верхнечелюстного синуса;</li><li>вопросы этиологии синусита;</li><li>современные алгоритмы диагностики и лечения синусита;</li></ul><p><strong>Практический курс включает в себя:</strong></p><ul><li>видеодемонстрацию эндоскопических вмешательств на верхнечелюстном синусе;</li><li>тренинг с видеоэндоскопической аппаратурой на фантоме;</li><li>тренинг по эндохирургии верхнечелюстного синуса в реальных условиях (секционный материал)</li></ul><p>При проведении данного курса особое внимание будет уделено малоинвазивным эндоскопическим технологиям диагностики и лечения синуситов. Врачам будет предоставлена возможность не только познакомиться с современными возможностями эндохирургии синуса, но и овладеть практическими навыками работы с эндоскопической аппаратурой и инструментарием.</p><p><strong>Программа курса:</strong><br />Обращаем Ваше внимание, что количество слушателей курса ограничено, поэтому просим регистрироваться заблаговременно!<br />Контактная информация: 8&nbsp;495 9894593 (Оксана, Олеся) e-mail:<script type="text/javascript"></script><a href="mailto:edu@amedpharm.ru"><span style="color: #000acc;">edu@amedpharm.ru</span></a><script type="text/javascript"></script><span style="display: none;">Этот адрес электронной почты защищен от спам-ботов. У вас должен быть включен JavaScript для просмотра.<script type="text/javascript"></script></span></p><table border="0"><tbody><tr><td colspan="2" bgcolor="#0c0c0c"><strong>1-ый день (теоретическая часть) 28 апреля, 2011</strong></td></tr><tr><td width="17%">09:00-10:00</td><td>Регистрация</td></tr><tr><td>10:00-11:50</td><td>Хирургическая анатомия и физиология верхнечелюстного синуса и смежных структур</td></tr><tr><td>11:55-12:40</td><td>Современные представления об этиологии одонтогенных верхнечелюстных синуситов</td></tr><tr><td>12:45-13:30</td><td>Современный алгоритм диагностики состояния верхнечелюстного синуса</td></tr><tr><td>13:30-14:30</td><td>Ланч</td></tr><tr><td>14:30-16:00</td><td>Современная концепция хирургии верхнечелюстного синусита. Методики открытой и эндоскопической хирургии</td></tr><tr><td>16:00-17:00</td><td>Медикаментозная терапия одонтогенных верхнечелюстных синуситов анестезиологическое обеспечение вмешательств на верхнечелюстном синусе</td></tr><tr><td>17:30-18:00</td><td>Осложнения хирургии верхнечелюстного синуса</td></tr><tr><td>17:00-19:00</td><td>Мануальный тренинг, работа с эндоскопической техникой на фантоме</td></tr><tr><td colspan="2" bgcolor="#0c0c0c"><strong>2-ой день (практическая часть) 29 апреля, 2011</strong></td></tr><tr><td>10:00-12:00</td><td>1 группа: Мануальный тренинг в реальных условиях (работа с секционным материалом)</td></tr><tr><td>12:00-14:00</td><td><p>2 группа: Мануальный тренинг в реальных условиях (работа с секционным материалом)</p></td></tr><tr><td colspan="2" bgcolor="#0c0c0c"><strong>Стоимость обучения:</strong></td></tr><tr><td>только теория :</td><td>8400 руб. (1 день)</td></tr><tr><td>теория &nbsp;и практика :</td><td>14000 рублей (2 дня)</td></tr></tbody></table><p>&nbsp;</p><table border="0"><tbody><tr><td style="text-align: center;"><strong>Руководитель курса:</strong></td><td style="text-align: center;" colspan="2"><strong>Преподаватели курса:</strong></td></tr><tr><td><img title="photo_ssp_small.jpg" src="/public/media/news/photo_ssp_small.jpg" alt="photo_ssp_small.jpg" width="140" height="200" /></td><td><img title="photo_dss_small.jpg" src="/public/media/news/photo_dss_small.jpg" alt="photo_dss_small.jpg" width="140" height="200" /></td><td><img title="photo_kvv_small.jpg" src="/public/media/news/photo_kvv_small.jpg" alt="photo_kvv_small.jpg" width="140" height="200" /></td></tr><tr><td>Сысолятин Святослав Павлович, д.м.н., профессор, заведующий кафедрой факультетской хирургической стоматологии Первого московского государственного медицинского университета имени И.М.Сеченова</td><td>Дыдыкин Сергей Сергеевич, д.м.н., профессор кафедры оперативной хирургии и топографической анатомии Первого московского государственного медицинского университета имени И.М.Сеченова</td><td>Коробов Валерий Васильевич, врач-анестезиолог высшей категории, сотрудник Первого московского государственного медицинского университета имени И.М.Сеченова</td></tr></tbody></table><table border="0"><tbody><tr><td style="text-align: center;" colspan="2"><p><strong>Место проведения (лекционный курс): </strong><br />Москва, Можайское шоссе, д.2</p><p>учебный центр клиники &laquo;Мать и дитя&raquo;</p></td></tr><tr><td><img title="map_1.jpg" src="/public/media/news/map_1.jpg" alt="map_1.jpg" width="375" height="375" /></td><td><img title="map_2.jpg" src="/public/media/news/map_2.jpg" alt="map_2.jpg" width="375" height="375" /></td></tr><tr><td><strong>От станции м. Кунцевская: </strong><br />Выход из второго вагона из центра,?повернуть направо,?далее &ndash; можно дойти пешком за 10 мин.?или?доехать до остановки &laquo;ул. Вересаева&raquo; авт.45, 190, 610, 612</td><td><strong>От станции м.Славянский бульвар: </strong><br />Выход из метро в сторону Кутузовского проспекта,?далее &ndash;?доехать до остановки &laquo;ул. Вересаева&raquo; авт.10, 103 или маршрутным такси 818.</td></tr></tbody></table><p>&nbsp;Дополнительная информация на сайте: <a href="http://amfv.ru/">http://amfv.ru/</a></p>', '');

-- --------------------------------------------------------

--
-- Table structure for table `index`
--

CREATE TABLE `index` (
  `page_id` int(11) NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `annotation` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`page_id`),
  FULLTEXT KEY `title` (`title`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `index`
--

INSERT INTO `index` (`page_id`, `url`, `created_on`, `updated_on`, `title`, `content`, `annotation`) VALUES
(1, '', '2012-08-17 15:11:49', '2012-09-05 16:10:15', 'home ', 'projectbegin 1996 stanford univ graduat student larri page and sergei brin built search engin call &ldquo backrub&rdquo us link determin import individu web page 1998 thei had formal their work creat compani you know todai google.more project syndicatearticl rss feed ', 'Home'),
(2, '', '2012-08-17 15:11:49', '2012-09-05 16:10:15', 'articl ', '', 'Articles'),
(3, '', '2012-08-17 15:11:49', '2012-09-05 16:10:15', 'проект ', 'псков мин ноч неизвестн оскверн собор иоа предтеч иоанновск женск монастыр стен собор поя две огромн надпис дол церковн мракобес уваж pussi riot работник офисн центр располож неподалек рассказ интерфакс вчер вечер надпис обнаруж пятниц утр прид работу.в амовническ суд москв сегодн должн состо оглаш приговор дел участниц групп pussi riot котор обвин улиганств рам рист спасител приговор надежд толоконник мар ал ин екатерин самуцевич начнут зачитыв 15:00 московск врем проис од процесс буд прям эфир транслиров сайт амовническ суда.в москв неизвестн сторонник групп pussi riot устр флешмоб од котор активист наряж памятник столиц балакла аналогичн котор выступ участниц панк-молебна рам рист спасител ', 'О проекте'),
(4, '', '2012-08-17 15:11:49', '2012-09-05 16:10:15', 'contact ', '1600 amphitheatr parkwaymountain viewca 94043 ', 'Contacts'),
(5, '', '2012-08-17 15:11:49', '2012-09-05 16:10:15', 'web gap between scienc and public ', 'we recent held innov workshop 2011 googl scienc commun fellow group earli mid-career phd scientist chosen their leadership climat chang research and commun fellow spent three dai togeth alongsid googler and extern expert googleplex mountain view calif explor potenti inform technolog and social media spur public engag all 21 2011 fellow experienc scienc commun train us tradit media bridg gap between complex scienc and gener public workshop opportun them explor new media commun optim ag web like sai learn &ldquo web&rdquo gap between scienc commun and larger world digit ag we organ workshop around three themes:understanding public session introduc trend tools&mdash like search googl trend and correlate&mdash can us gather data search queri and onlin discuss if you&rsquo re curiou watch googl user experi research dan russel give fellow 101 peopl search and they&rsquo re look for.documenting your scienc stori here fellow plai around googl earth fusion tabl and youtub learn creat interact and engag stori scienc data which could then share broad audienc more visit scienc commun fellow talk page youtube.joining conver session googler chri messina develop advoc took fellow journei into social web illustr exampl power crowd shape idea and build understand across diver social network you can view chris&rsquo s outstand talk here.several extern expert particip workshop well includ andi revkin dot earth blogger and senior fellow environ understand pace univ andi gave thought-provoking keynot first even which also includ self-composed ditti fossil ag look out schoolhou rock arm new knowledg &ldquo web gap &rdquo fellow now develop project propo put thei learn into practic propo select made later summer you can learn more tool scienc commun digit ag and innov workshop our site here stai tune futur opportun particip program we recent held innov workshop 2011 googl scienc commun fellow group earli mid-career phd scientist chosen their leadership climat chang research and commun fellow spent three dai togeth alongsid googler and extern expert googleplex mountain view calif explor potenti inform technolog and social media spur public engag ', 'Webbing the gap between science and the public'),
(6, '', '2012-08-17 15:11:49', '2012-09-05 16:10:15', 'googleserv 2011 give back around world ', 'over last month more than 7 700 googler help serv their commun across 400 differ project part googleserv employee-driven initi organ almost entir volunt through partnership nonprofit school and local govern googler 119 citi 36 countri help commun need project rang educ youth onlin bulli clean up local river and park googleserv began 2008 and ha becom annual compani tradit give back our commun not onli revit and strengthen our connect citi and town which we live and work also bring us closer togeth global team each year event ha grown size and scope and year&rsquo s googleserv our largest yet here&rsquo s sampl some project we particip time around new york we led resum write workshop and provid career coach iraq and afghanistan veteran america member seek employment.we help mountain ireland construct drain order maintain stretch trail along dublin mountain way.we facilit strateg plan session staff post prison educ program seattle.we conduct onlin tool workshop ngo singapor nation volunt and philanthropi centre.we fix up bike free ride pittsburgh which donat local nonprofit and residents.at punjabi bagh central market area west delhi we clean and remov old decai poster help &ldquo let do delhi &rdquo organ which ha taken up initi minimi abu public property.we provid one-on-one consult high potenti low incom women start their own busi women&rsquo s initi self emploi san francisco.we ran bookmak workshop elementari school children 826la venic ca.while googleserv annual celebr commun servic employ donat both time and monei organ and cau throughout year you can find opportun serv your local commun all good over last month more than 7 700 googler help serv their commun across 400 differ project part googleserv employee-driven initi organ almost entir volunt through partnership nonprofit school and local govern googler 119 citi 36 countri help commun need project rang educ youth onlin bulli clean up local river and park ', 'GoogleServe 2011: Giving back around the world'),
(7, '', '2012-08-17 15:11:49', '2012-09-05 16:10:15', 'seek americas’ brightest young mind spot zeitgeist america ', 'year we&rsquo re mix up our annual zeitgeist confer launch young mind competit host youth engag agenc liviti support googl and host youtub start week we&rsquo re search 12 inspir young peopl make impact their world attend zeitgeist america 2011&mdash our annual gather 400+ busi and thought leader across contin held each year paradi vallei ariz winner invit two-day event they&rsquo ll take part seri tailor master class host googl and meet some most power and thought-provoking peopl planet we want pioneer changemak and leader tomorrow take their place alongsid greatest mind todai and us zeitgeist springboard which thei can continu do amaz thing make world better place.the young mind competit open peopl ag 18-24 u.s canada mexico brazil and argentina your chanc win slot zeitgeist america 2011 go towww.zeitgeistyoungminds.com befor august 25 and upload video show us you&rsquo re make posit impact world.update 7/8 although previou state competit open all america plea note onli open peopl u.s canada mexico brazil and argentina year we&rsquo re mix up our annual zeitgeist confer launch young mind competit host youth engag agenc liviti support googl and host youtub start week we&rsquo re search 12 inspir young peopl make impact their world attend zeitgeist america 2011&mdash our annual gather 400+ busi and thought leader across contin held each year paradi vallei ariz ', 'Seeking the Americas’ brightest young minds for a spot at Zeitgeist Americas'),
(13, '', '2012-08-17 15:11:49', '2012-09-05 16:10:15', 'examin impact clean energi innov ', 'googl we&rsquo re commit us technolog solv greatest challeng we face countri build clean energi futur that&rsquo s why we&rsquo ve work hard carbon neutral compani launch our renew energi cheaper than coal initi and haveinvest sever clean energi compani and project around world but if we knew valu innov clean energi technolog much could new technolog contribut our econom growth enhanc our energi secur reduc greenhou ga ghg emiss robust data can help us understand these import question and role innov clean energi could plai address our futur econom secur and climat challenges.through google.org our energi team set out answer some these question usingmckinsey&rsquo s low carbon econom tool lcet we assess long-term econom impact u.s assum breakthrough were made sever differ clean energi technolog like wind geotherm and electr vehicl mckinsey&rsquo s lcet neutral analyt set interlink model estim potenti econom and technolog implic variou polici and technolog assumpt analysi base model and includ assumpt and conclu google.org develop so isn&rsquo t predict futur we&rsquo ve decid make analysi and associ data avail everywh becau we believ could provid new perspect econom valu public and privat invest energi innov here just some most compel find energi innov pai off big we compar &ldquo busi usual&rdquo bau scenario breakthrough clean energi technolog top those we layer seri possibl clean energi polici more detail report we found 2030 compar bau breakthrough could help u.s.:grow gdp over $155 billion/year $244 billion our clean polici scenario creat over 1.1 million new full-time jobs/year 1.9 million clean polici reduc household energi cost over $942/year $995 clean polici reduc u.s oil consumpt over 1.1 billion barrels/yearreduce u.s total carbon emiss 13% 2030 21% clean polici speed matter and delai costli our model found mere five year delai 2010-2015 accel technolog innov led $2.3-3.2 trillion unreal gdp aggreg 1.2-1.4 million net unreal job and 8-28 more gigaton potenti ghg emiss 2050.policy and innov can enhanc each other combin clean energi polici technolog breakthrough increa econom secur and pollut benefit either innov polici alon take ghg emiss model show combin polici and innov led 59% ghg reduct 2050 vs 2005 level while maintain econom growth.this analysi assum breakthrough clean energi happen and polici were put place and then tri understand impact data here allow us imagin world which u.s captur potenti benefit some clean energi technolog econom growth job gener and reduct harm emiss we haven&rsquo t develop roadmap and get there take right mix polici sustain invest technolog innov public and privat institut and mobil privat sector&rsquo s entrepreneuri energi we hope analysi encourag further discuss and debat these import issu googl we&rsquo re commit us technolog solv greatest challeng we face countri build clean energi futur that&rsquo s why we&rsquo ve work hard carbon neutral compani launch our renew energi cheaper than coal initi and haveinvest sever clean energi compani and project around world ', 'Examining the impact of clean energy innovation'),
(21, '', '2012-08-21 12:50:06', '2012-09-05 16:10:15', 'поиск ', 'результат поискап ваш запрос найд ', 'Поиск'),
(23, '', '2012-08-21 12:50:06', '2012-09-05 16:10:15', 'qa ', 'зад одн вопрос ', 'QA'),
(12, '', '2012-09-05 15:06:02', '2012-09-05 16:10:15', 'celebr pride 2011 ', 'more than thousand googler particip pride celebr dozen citi support equal and rememb sacrif those have made life better member lgbt* commun todai while we celebr legal marriag equal new york state gai right movement unit state began more than 40 year ago our particip especi global year we were mardi gra sydnei australia first time and support pink dot singapor san francisco dublin tel aviv boston we step out larg number pride parad around world color swirl gaygler and android pride t-shirts year past we featur month-long easter egg our search result worldwid celebr pride ad rainbow next search box number pride-related queri includ [lgbt] [marriage equality] and [pride 2011] ', 'Celebrating Pride 2011');

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE `page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `breadcrumb` varchar(160) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` text,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `layout_file` varchar(250) NOT NULL,
  `behavior_id` varchar(25) NOT NULL,
  `status_id` int(11) unsigned NOT NULL DEFAULT '100',
  `created_on` datetime DEFAULT NULL,
  `published_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `position` mediumint(6) unsigned DEFAULT NULL,
  `needs_login` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `title`, `slug`, `breadcrumb`, `keywords`, `description`, `parent_id`, `layout_file`, `behavior_id`, `status_id`, `created_on`, `published_on`, `updated_on`, `created_by_id`, `updated_by_id`, `position`, `needs_login`) VALUES
(1, 'Home', '', 'Home', '', '', 0, 'normal', '', 100, '2012-08-15 11:02:20', '2012-08-15 11:02:21', '2012-09-05 15:07:46', 1, 1, 0, 0),
(2, 'Articles', 'articles', 'Articles', '', '', 1, '', 'archive', 100, '2012-08-15 11:02:23', '2012-08-15 11:02:24', '2012-08-16 18:06:57', 1, 1, 0, 2),
(3, 'О проекте', 'about-project', 'О проекте', '', '', 1, '', '', 100, '2012-08-15 11:02:26', '2012-08-15 11:02:27', '2012-08-17 16:00:12', 1, 1, 5, 2),
(4, 'Contacts', 'contacts', 'Contacts', '', '', 1, '', '', 100, '2012-08-15 11:02:29', '2012-08-15 11:02:30', '2012-08-15 17:13:35', 1, 1, 4, 2),
(5, 'Webbing the gap between science and the public', 'webbing-the-gap-between-science-and-the-public', 'Webbing the gap between science and the public', '', '', 2, '', '', 100, '2012-08-15 11:02:32', '2012-08-15 11:02:33', '2012-08-15 11:02:34', 1, 1, 5, 2),
(6, 'GoogleServe 2011: Giving back around the world', 'googleserve-2011-giving-back-around-the-world', 'GoogleServe 2011: Giving back around the world', '', '', 2, '', '', 100, '2012-08-15 11:02:35', '2012-08-15 11:02:36', '2012-08-15 11:02:37', 1, 1, 9, 2),
(7, 'Seeking the Americas’ brightest young minds for a spot at Zeitgeist Americas', 'seeking-the-americas-brightest-young-minds-for-a-spot-at-zeitgeist-americas', 'Seeking the Americas’ brightest young minds for a spot at Zeitgeist Americas', '', '', 2, '', '', 100, '2012-08-15 11:02:38', '2012-08-15 11:02:39', '2012-08-15 11:02:40', 1, 1, 6, 2),
(13, 'Examining the impact of clean energy innovation', 'examining-the-impact-of-clean-energy-innovation', 'Examining the impact of clean energy innovation', '', '', 2, '', '', 100, '2012-08-15 11:02:41', '2012-08-15 11:02:42', '2012-08-15 11:02:43', 1, 1, 8, 2),
(12, 'Celebrating Pride 2011', 'celebrating-pride-2011', 'Celebrating Pride 2011', '', '', 2, '', '', 100, '2012-08-15 11:02:44', '2012-08-15 11:02:45', '2012-08-15 11:02:46', 1, 1, 7, 2),
(8, '%B %Y archive', 'monthly_archive', '%B %Y archive', '', '', 2, '', 'archive_month_index', 101, '2012-08-15 11:02:47', '2012-08-15 11:02:48', '2012-08-16 14:41:59', 1, 1, 0, 2),
(9, 'Sitemap XML', 'sitemap.xml', 'Sitemap XML', '', '', 1, 'sietmap.xml', '', 101, '2012-08-15 11:02:50', '2012-08-15 11:02:51', '2012-08-15 17:13:35', 1, 1, 1, 2),
(10, 'Page not found', 'page-not-found', 'Page not found', '', '', 1, '', 'page_not_found', 101, '2012-08-15 11:02:53', '2012-08-15 11:02:54', '2012-08-15 17:13:35', 1, 1, 3, 2),
(11, 'RSS XML Feed', 'rss.xml', 'RSS XML Feed', '', '', 1, 'rss.xml', '', 101, '2012-08-15 11:02:56', '2012-08-15 11:02:57', '2012-08-15 17:13:35', 1, 1, 2, 2),
(21, 'Поиск', 'search', 'Поиск', '', '', 1, '', 'search_result', 100, '2012-08-17 16:24:20', '2012-08-17 16:24:20', '2012-09-05 17:36:57', 1, 1, 6, 2),
(23, 'QA', 'qa', 'QA', '', '', 1, 'normal', '', 100, '2012-08-20 10:54:30', '2012-08-20 10:54:12', '2012-08-22 14:03:25', 1, 1, 7, 0),
(24, 'view', 'view', 'view', '', '', 23, '', '', 101, '2012-08-20 11:59:44', '2012-08-20 11:59:37', '2012-08-20 14:29:19', 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `page_part`
--

CREATE TABLE `page_part` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `filter_id` varchar(25) DEFAULT NULL,
  `content` longtext,
  `content_html` longtext,
  `page_id` int(11) unsigned DEFAULT NULL,
  `is_protected` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `page_part`
--

INSERT INTO `page_part` (`id`, `name`, `filter_id`, `content`, `content_html`, `page_id`, `is_protected`) VALUES
(2, 'sidebar', '', '<h3>About project</h3><p>Beginning in 1996, Stanford University graduate students Larry Page and Sergey Brin built a search engine called &ldquo;BackRub&rdquo; that used links to determine the importance of individual web pages. By 1998 they had formalized their work, creating the company you know today as Google.</p><p><a href="/about-project.html">More about project &raquo;</a></p><h3>Syndicate</h3><p><a href="/rss.xml">Articles RSS Feed</a></p>', '<h3>About project</h3><p>Beginning in 1996, Stanford University graduate students Larry Page and Sergey Brin built a search engine called &ldquo;BackRub&rdquo; that used links to determine the importance of individual web pages. By 1998 they had formalized their work, creating the company you know today as Google.</p><p><a href="/about-project.html">More about project &raquo;</a></p><h3>Syndicate</h3><p><a href="/rss.xml">Articles RSS Feed</a></p>', 1, 0),
(3, 'body', 'tinymce', '<p>We recently held an Innovation Workshop for the 2011&nbsp;<a href="http://googleblog.blogspot.com/2011/02/making-sense-of-science-introducing.html">Google Science Communication Fellows</a>, a group of early to mid-career PhD scientists chosen for their leadership in climate change research and communication. The Fellows spent three days together alongside Googlers and external experts at the Googleplex in Mountain View, Calif. exploring the potential of information technology and social media to spur public engagement.&nbsp;<br /><br />All 21 of the&nbsp;<a href="http://www.google.com/edu/science/fellows.html">2011 Fellows</a>&nbsp;are experienced science communicators, trained in using traditional media to bridge the gap between complex science and the general public. This workshop was an opportunity for them to explore new media communications optimized for the age of the web; or, as as I like to say, learning how to &ldquo;web&rdquo; the gap between the science community and the larger world in the digital age.&nbsp;<br />We organized the workshop around three themes:</p><ol><li><strong>Understanding the public.</strong>&nbsp;This session introduced trending tools&mdash; like search,<a href="http://www.google.com/trends">Google Trends</a>&nbsp;and&nbsp;<a href="http://correlate.googlelabs.com/">Correlate</a>&mdash;that can be used to gather data from search queries and online discussions. If you&rsquo;re curious,&nbsp;<a href="http://www.youtube.com/watch?v=63lgyeJnRJI">watch</a>&nbsp;Google user experience researcher, Dan Russel, give the Fellows a 101 on how people search, and what they&rsquo;re looking for.</li><li><strong>Documenting your science story.</strong>&nbsp;Here, the Fellows played around with&nbsp;<a href="http://www.google.com/earth/index.html">Google Earth</a>,&nbsp;<a href="http://www.google.com/fusiontables/public/tour/index.html">Fusion Tables</a>&nbsp;and&nbsp;<a href="http://www.youtube.com/">YouTube</a>&nbsp;to learn how to create interactive and engaging stories with science data, which could then be shared with a broad audience. For more on this, visit the&nbsp;<a href="http://www.youtube.com/watch?v=lZ7PJOwSh8k">Science Communications Fellows talks page</a>&nbsp;on YouTube.</li><li><strong>Joining the conversation.</strong>&nbsp;In this session, Googler Chris Messina, a developer advocate, took the Fellows on a journey into the social web, illustrating by examples the power of the crowd in shaping ideas and building understanding across diverse social networks. You can view Chris&rsquo;s outstanding talk&nbsp;<a href="http://www.youtube.com/watch?v=IrTSiO9ejOs">here</a>.</li></ol><p>Several external experts participated in the workshop as well, including Andy Revkin,&nbsp;<a href="http://dotearth.blogs.nytimes.com/">Dot Earth</a>&nbsp;blogger and senior fellow of environmental understanding at Pace University. Andy gave a thought-provoking&nbsp;<a href="http://www.youtube.com/watch?v=lU_4OR3hOyo">keynote</a>&nbsp;the first evening, which also included a self-composed ditty about the fossil age (look out&nbsp;<em>Schoolhouse Rock!</em>).<br /><br />Armed with new knowledge on &ldquo;webbing the gap,&rdquo; the Fellows are now developing project proposals to put what they learned into practice. Proposal selections will be made later this summer. You can learn more about tools for science communication in the digital age and the innovation workshop at our site&nbsp;<a href="http://www.google.com/edu/science/">here</a>. Stay tuned for future opportunities for participating in this program.</p>', '<p>We recently held an Innovation Workshop for the 2011&nbsp;<a href="http://googleblog.blogspot.com/2011/02/making-sense-of-science-introducing.html">Google Science Communication Fellows</a>, a group of early to mid-career PhD scientists chosen for their leadership in climate change research and communication. The Fellows spent three days together alongside Googlers and external experts at the Googleplex in Mountain View, Calif. exploring the potential of information technology and social media to spur public engagement.&nbsp;<br /><br />All 21 of the&nbsp;<a href="http://www.google.com/edu/science/fellows.html">2011 Fellows</a>&nbsp;are experienced science communicators, trained in using traditional media to bridge the gap between complex science and the general public. This workshop was an opportunity for them to explore new media communications optimized for the age of the web; or, as as I like to say, learning how to &ldquo;web&rdquo; the gap between the science community and the larger world in the digital age.&nbsp;<br />We organized the workshop around three themes:</p><ol><li><strong>Understanding the public.</strong>&nbsp;This session introduced trending tools&mdash; like search,<a href="http://www.google.com/trends">Google Trends</a>&nbsp;and&nbsp;<a href="http://correlate.googlelabs.com/">Correlate</a>&mdash;that can be used to gather data from search queries and online discussions. If you&rsquo;re curious,&nbsp;<a href="http://www.youtube.com/watch?v=63lgyeJnRJI">watch</a>&nbsp;Google user experience researcher, Dan Russel, give the Fellows a 101 on how people search, and what they&rsquo;re looking for.</li><li><strong>Documenting your science story.</strong>&nbsp;Here, the Fellows played around with&nbsp;<a href="http://www.google.com/earth/index.html">Google Earth</a>,&nbsp;<a href="http://www.google.com/fusiontables/public/tour/index.html">Fusion Tables</a>&nbsp;and&nbsp;<a href="http://www.youtube.com/">YouTube</a>&nbsp;to learn how to create interactive and engaging stories with science data, which could then be shared with a broad audience. For more on this, visit the&nbsp;<a href="http://www.youtube.com/watch?v=lZ7PJOwSh8k">Science Communications Fellows talks page</a>&nbsp;on YouTube.</li><li><strong>Joining the conversation.</strong>&nbsp;In this session, Googler Chris Messina, a developer advocate, took the Fellows on a journey into the social web, illustrating by examples the power of the crowd in shaping ideas and building understanding across diverse social networks. You can view Chris&rsquo;s outstanding talk&nbsp;<a href="http://www.youtube.com/watch?v=IrTSiO9ejOs">here</a>.</li></ol><p>Several external experts participated in the workshop as well, including Andy Revkin,&nbsp;<a href="http://dotearth.blogs.nytimes.com/">Dot Earth</a>&nbsp;blogger and senior fellow of environmental understanding at Pace University. Andy gave a thought-provoking&nbsp;<a href="http://www.youtube.com/watch?v=lU_4OR3hOyo">keynote</a>&nbsp;the first evening, which also included a self-composed ditty about the fossil age (look out&nbsp;<em>Schoolhouse Rock!</em>).<br /><br />Armed with new knowledge on &ldquo;webbing the gap,&rdquo; the Fellows are now developing project proposals to put what they learned into practice. Proposal selections will be made later this summer. You can learn more about tools for science communication in the digital age and the innovation workshop at our site&nbsp;<a href="http://www.google.com/edu/science/">here</a>. Stay tuned for future opportunities for participating in this program.</p>', 5, 0),
(5, 'body', '', 'В Пскове минувшей ночью неизвестные осквернили собор Иоанна Предтечи, бывший Иоанновский женский монастырь. На стенах собора появились две огромных надписи: "Долой церковных мракобесов" и "Уважуха Pussy Riot".Работники офисного центра, расположенного неподалеку, рассказали "Интерфаксу", что вчера вечером надписи еще не было, и они обнаружили ее в пятницу утром, придя на работу.В Хамовническом суде Москвы сегодня должно состояться оглашение приговора по делу участниц группы Pussy Riot, которые обвиняются в хулиганстве в Храме Христа Спасителя. Приговор Надежде Толоконниковой, Марии Алехиной и Екатерине Самуцевич начнут зачитывать в 15:00 по московскому времени. Происходящее на процессе будем в прямом эфире транслироваться на сайте Хамовнического суда.В Москве неизвестные сторонники группы Pussy Riot устроили флешмобы, в ходе которых активисты "наряжали" памятники столицы в балаклавы, аналогичные тем, в которых выступали участницы панк-молебна в Храме Христа Спасителя.', 'В Пскове минувшей ночью неизвестные осквернили собор Иоанна Предтечи, бывший Иоанновский женский монастырь. На стенах собора появились две огромных надписи: "Долой церковных мракобесов" и "Уважуха Pussy Riot".Работники офисного центра, расположенного неподалеку, рассказали "Интерфаксу", что вчера вечером надписи еще не было, и они обнаружили ее в пятницу утром, придя на работу.В Хамовническом суде Москвы сегодня должно состояться оглашение приговора по делу участниц группы Pussy Riot, которые обвиняются в хулиганстве в Храме Христа Спасителя. Приговор Надежде Толоконниковой, Марии Алехиной и Екатерине Самуцевич начнут зачитывать в 15:00 по московскому времени. Происходящее на процессе будем в прямом эфире транслироваться на сайте Хамовнического суда.В Москве неизвестные сторонники группы Pussy Riot устроили флешмобы, в ходе которых активисты "наряжали" памятники столицы в балаклавы, аналогичные тем, в которых выступали участницы панк-молебна в Храме Христа Спасителя.', 3, 0),
(6, 'body', 'tinymce', '<p>1600 Amphitheatre Parkway<br />Mountain View<br />CA 94043</p>', '<p>1600 Amphitheatre Parkway<br />Mountain View<br />CA 94043</p>', 4, 0),
(7, 'short', 'tinymce', '<p>We recently held an Innovation Workshop for the 2011&nbsp;<a href="http://googleblog.blogspot.com/2011/02/making-sense-of-science-introducing.html">Google Science Communication Fellows</a>, a group of early to mid-career PhD scientists chosen for their leadership in climate change research and communication. The Fellows spent three days together alongside Googlers and external experts at the Googleplex in Mountain View, Calif. exploring the potential of information technology and social media to spur public engagement.&nbsp;</p>', '<p>We recently held an Innovation Workshop for the 2011&nbsp;<a href="http://googleblog.blogspot.com/2011/02/making-sense-of-science-introducing.html">Google Science Communication Fellows</a>, a group of early to mid-career PhD scientists chosen for their leadership in climate change research and communication. The Fellows spent three days together alongside Googlers and external experts at the Googleplex in Mountain View, Calif. exploring the potential of information technology and social media to spur public engagement.&nbsp;</p>', 5, 0),
(8, 'body', 'tinymce', '<p>At Google, we&rsquo;re committed to using technology to solve one of the greatest challenges we face as a country: building a clean energy future. That&rsquo;s why we&rsquo;ve worked hard to be carbon neutral as a company, launched our&nbsp;<a href="http://www.google.org/rec.html">renewable energy cheaper than coal</a>&nbsp;initiative and have<a href="http://www.google.com/green/collaborations/support-innovations.html">invested</a>&nbsp;in several clean energy companies and projects around the world.&nbsp;<br /><br />But what if we knew the value of innovation in clean energy technologies? How much could new technologies contribute to our economic growth, enhance our energy security or reduce greenhouse gas (GHG) emissions? Robust data can help us understand these important questions, and the role innovation in clean energy could play in addressing our future economic, security and climate challenges.<br /><br />Through Google.org, our energy team set out to answer some of these questions. Using<a href="http://www.mckinsey.com/clientservice/sustainability/low_carbon_economics_tool.asp">McKinsey&rsquo;s Low Carbon Economics Tool</a>&nbsp;(LCET), we assessed the long-term economic impacts for the U.S. assuming breakthroughs were made in several different clean energy technologies, like wind, geothermal and electric vehicles. McKinsey&rsquo;s LCET is a neutral, analytic set of interlinked models that estimates the potential economic and technology implications of various policy and technology assumptions.&nbsp;<br /><br />The analysis is based on a model and includes assumptions and conclusions that Google.org developed, so it isn&rsquo;t a prediction of the future. We&rsquo;ve decided to make the&nbsp;<a href="http://www.google.org/energyinnovation">analysis and associated data</a>&nbsp;available everywhere because we believe it could provide a new perspective on the economic value of public and private investment in energy innovation. Here are just some of the most compelling findings:&nbsp;</p><ul><li><strong>Energy innovation pays off big:</strong>&nbsp;We compared &ldquo;business as usual&rdquo; (BAU) to scenarios with breakthroughs in clean energy technologies. On top of those, we layered a series of possible clean energy policies (more details in the&nbsp;<a href="http://www.google.org/energyinnovation">report</a>). We found that by 2030, when compared to BAU, breakthroughs could help the U.S.:</li><ul><li>Grow GDP by over $155 billion/year ($244 billion in our Clean Policy scenario)</li><li>Create over 1.1 million new full-time jobs/year (1.9 million with Clean Policy)</li><li>Reduce household energy costs by over $942/year ($995 with Clean Policy)</li><li>Reduce U.S. oil consumption by over 1.1 billion barrels/year</li><li>Reduce U.S. total carbon emissions by 13% in 2030 (21% with Clean Policy)</li></ul><li><strong>Speed matters and delay is costly:</strong>&nbsp;Our model found a mere five year delay (2010-2015) in accelerating technology innovation led to $2.3-3.2 trillion in unrealized GDP, an aggregate 1.2-1.4 million net unrealized jobs and 8-28 more gigatons of potential GHG emissions by 2050.</li><li><strong>Policy and innovation can enhance each other:</strong>&nbsp;Combining clean energy policies with technological breakthroughs increased the economic, security and pollution benefits for either innovation or policy alone. Take GHG emissions: the model showed that combining policy and innovation led to 59% GHG reductions by 2050 (vs. 2005 levels), while maintaining economic growth.</li></ul><p>This analysis assumed that breakthroughs in clean energy happened and that policies were put in place, and then tried to understand the impact. The data here allows us to imagine a world in which the U.S. captures the potential benefits of some clean energy technologies: economic growth, job generation and a reduction in harmful emissions. We haven&rsquo;t developed the roadmap, and getting there will take the right mix of policies, sustained investment in technological innovation by public and private institutions and mobilization of the private sector&rsquo;s entrepreneurial energies. We hope this analysis encourages further discussion and debate on these important issues.</p>', '<p>At Google, we&rsquo;re committed to using technology to solve one of the greatest challenges we face as a country: building a clean energy future. That&rsquo;s why we&rsquo;ve worked hard to be carbon neutral as a company, launched our&nbsp;<a href="http://www.google.org/rec.html">renewable energy cheaper than coal</a>&nbsp;initiative and have<a href="http://www.google.com/green/collaborations/support-innovations.html">invested</a>&nbsp;in several clean energy companies and projects around the world.&nbsp;<br /><br />But what if we knew the value of innovation in clean energy technologies? How much could new technologies contribute to our economic growth, enhance our energy security or reduce greenhouse gas (GHG) emissions? Robust data can help us understand these important questions, and the role innovation in clean energy could play in addressing our future economic, security and climate challenges.<br /><br />Through Google.org, our energy team set out to answer some of these questions. Using<a href="http://www.mckinsey.com/clientservice/sustainability/low_carbon_economics_tool.asp">McKinsey&rsquo;s Low Carbon Economics Tool</a>&nbsp;(LCET), we assessed the long-term economic impacts for the U.S. assuming breakthroughs were made in several different clean energy technologies, like wind, geothermal and electric vehicles. McKinsey&rsquo;s LCET is a neutral, analytic set of interlinked models that estimates the potential economic and technology implications of various policy and technology assumptions.&nbsp;<br /><br />The analysis is based on a model and includes assumptions and conclusions that Google.org developed, so it isn&rsquo;t a prediction of the future. We&rsquo;ve decided to make the&nbsp;<a href="http://www.google.org/energyinnovation">analysis and associated data</a>&nbsp;available everywhere because we believe it could provide a new perspective on the economic value of public and private investment in energy innovation. Here are just some of the most compelling findings:&nbsp;</p><ul><li><strong>Energy innovation pays off big:</strong>&nbsp;We compared &ldquo;business as usual&rdquo; (BAU) to scenarios with breakthroughs in clean energy technologies. On top of those, we layered a series of possible clean energy policies (more details in the&nbsp;<a href="http://www.google.org/energyinnovation">report</a>). We found that by 2030, when compared to BAU, breakthroughs could help the U.S.:</li><ul><li>Grow GDP by over $155 billion/year ($244 billion in our Clean Policy scenario)</li><li>Create over 1.1 million new full-time jobs/year (1.9 million with Clean Policy)</li><li>Reduce household energy costs by over $942/year ($995 with Clean Policy)</li><li>Reduce U.S. oil consumption by over 1.1 billion barrels/year</li><li>Reduce U.S. total carbon emissions by 13% in 2030 (21% with Clean Policy)</li></ul><li><strong>Speed matters and delay is costly:</strong>&nbsp;Our model found a mere five year delay (2010-2015) in accelerating technology innovation led to $2.3-3.2 trillion in unrealized GDP, an aggregate 1.2-1.4 million net unrealized jobs and 8-28 more gigatons of potential GHG emissions by 2050.</li><li><strong>Policy and innovation can enhance each other:</strong>&nbsp;Combining clean energy policies with technological breakthroughs increased the economic, security and pollution benefits for either innovation or policy alone. Take GHG emissions: the model showed that combining policy and innovation led to 59% GHG reductions by 2050 (vs. 2005 levels), while maintaining economic growth.</li></ul><p>This analysis assumed that breakthroughs in clean energy happened and that policies were put in place, and then tried to understand the impact. The data here allows us to imagine a world in which the U.S. captures the potential benefits of some clean energy technologies: economic growth, job generation and a reduction in harmful emissions. We haven&rsquo;t developed the roadmap, and getting there will take the right mix of policies, sustained investment in technological innovation by public and private institutions and mobilization of the private sector&rsquo;s entrepreneurial energies. We hope this analysis encourages further discussion and debate on these important issues.</p>', 13, 0),
(9, 'short', 'tinymce', '<p>At Google, we&rsquo;re committed to using technology to solve one of the greatest challenges we face as a country: building a clean energy future. That&rsquo;s why we&rsquo;ve worked hard to be carbon neutral as a company, launched our&nbsp;<a href="http://www.google.org/rec.html">renewable energy cheaper than coal</a>&nbsp;initiative and have<a href="http://www.google.com/green/collaborations/support-innovations.html">invested</a>&nbsp;in several clean energy companies and projects around the world.&nbsp;</p>', '<p>At Google, we&rsquo;re committed to using technology to solve one of the greatest challenges we face as a country: building a clean energy future. That&rsquo;s why we&rsquo;ve worked hard to be carbon neutral as a company, launched our&nbsp;<a href="http://www.google.org/rec.html">renewable energy cheaper than coal</a>&nbsp;initiative and have<a href="http://www.google.com/green/collaborations/support-innovations.html">invested</a>&nbsp;in several clean energy companies and projects around the world.&nbsp;</p>', 13, 0),
(11, 'short', 'tinymce', '<p>More than a thousand Googlers participated in Pride celebrations in a dozen cities to support equality and remember the sacrifices of those who have made life better for members of the LGBT* community today. While we celebrated the&nbsp;<a href="http://www.prideagenda.org/Issues-Explained/Marriage-and-Family-Protection/NY-Business-for-Marriage.aspx">legalization</a>&nbsp;of marriage equality in New York, the state where the gay rights movement in the United States began more than 40 years ago, our participation was especially global this year: we were at&nbsp;<a href="http://google-au.blogspot.com/2011/03/google-joins-2011-sydney-gay-lesbian.html">Mardi Gras in Sydney</a>, Australia for the first time and&nbsp;<a href="http://pinkdotsg.blogspot.com/2011/06/pink-dot-2011-now-supported-by-google.html">supported Pink Dot in Singapore</a>. From San Francisco to Dublin to Tel Aviv to Boston, we stepped out in large numbers for Pride parades around the world in a colorful swirl of Gaygler and Android Pride t-shirts. As in years past, we featured a month-long easter egg in our search results worldwide to celebrate Pride, adding a rainbow next to the search box for a number of Pride-related queries including [<a href="http://www.google.com/search?q=lgbt">lgbt</a>], [<a href="http://www.google.com/search?q=marriage+equality">marriage equality</a>] and [<a href="http://www.google.com/search?q=pride+2011">pride 2011</a>].</p>', '<p>More than a thousand Googlers participated in Pride celebrations in a dozen cities to support equality and remember the sacrifices of those who have made life better for members of the LGBT* community today. While we celebrated the&nbsp;<a href="http://www.prideagenda.org/Issues-Explained/Marriage-and-Family-Protection/NY-Business-for-Marriage.aspx">legalization</a>&nbsp;of marriage equality in New York, the state where the gay rights movement in the United States began more than 40 years ago, our participation was especially global this year: we were at&nbsp;<a href="http://google-au.blogspot.com/2011/03/google-joins-2011-sydney-gay-lesbian.html">Mardi Gras in Sydney</a>, Australia for the first time and&nbsp;<a href="http://pinkdotsg.blogspot.com/2011/06/pink-dot-2011-now-supported-by-google.html">supported Pink Dot in Singapore</a>. From San Francisco to Dublin to Tel Aviv to Boston, we stepped out in large numbers for Pride parades around the world in a colorful swirl of Gaygler and Android Pride t-shirts. As in years past, we featured a month-long easter egg in our search results worldwide to celebrate Pride, adding a rainbow next to the search box for a number of Pride-related queries including [<a href="http://www.google.com/search?q=lgbt">lgbt</a>], [<a href="http://www.google.com/search?q=marriage+equality">marriage equality</a>] and [<a href="http://www.google.com/search?q=pride+2011">pride 2011</a>].</p>', 12, 0),
(12, 'body', 'tinymce', '<p>Over the last month, more than 7,700 Googlers helped serve their communities across 400 different projects as part of GoogleServe, an employee-driven initiative organized almost entirely by volunteers. Through partnerships with nonprofits, schools and local governments, Googlers from 119 cities in 36 countries helped communities in need with projects ranging from educating youth about online bullying to cleaning up local rivers and parks.&nbsp;<br /><br />GoogleServe began in 2008 and has become an annual company tradition. Giving back to our communities not only revitalizes and strengthens our connections with the cities and towns in which we live and work, it also brings us closer together as a global team. Each year the event has grown in size and scope and this year&rsquo;s GoogleServe was our largest yet. Here&rsquo;s a sampling of some of the projects we participated in this time around:&nbsp;</p><ul><li>In New York, we led resume writing workshops and provided career coaching to&nbsp;<a href="http://iava.org/blog/iava-teams-google-job-skills-workshops">Iraq and Afghanistan Veterans of America</a>&nbsp;members seeking employment.</li><li>We helped&nbsp;<a href="http://www.mountaineering.ie/">Mountaineering Ireland</a>&nbsp;construct drains in order to maintain a stretch of trail along the Dublin Mountains Way.</li><li>We facilitated a strategic planning session for staff from the&nbsp;<a href="http://postprisonedu.org/">Post Prison Education Program</a>&nbsp;in Seattle.</li><li>We conducted an online tools workshop for NGOs in Singapore with the&nbsp;<a href="http://www.nvpc.org.sg/pgm/others/nvpc_f_default_public.aspx">National Volunteer and Philanthropy Centre</a>.</li><li>We fixed up bikes with&nbsp;<a href="http://freeridepgh.org/volunteer/how-to-volunteer/ways-to-volunteer/">Free Ride</a>&nbsp;in Pittsburgh, which will be donated to local nonprofits and residents.</li><li>At the&nbsp;<a href="http://alpha.mapmyindia.com/mcdApp/">Punjabi Bagh Central Market</a>&nbsp;area in West Delhi, we cleaned and removed old, decayed posters with the help of &ldquo;<a href="http://www.letsdoitdelhi.org/">Lets do it Delhi</a>,&rdquo; an organization which has taken up the initiative to minimise abuse of public property.</li><li>We provided one-on-one consultations with high potential, low income women starting their own businesses with the&nbsp;<a href="http://www.womensinitiative.org/index.htm">Women&rsquo;s Initiative for Self Employment</a>&nbsp;in San Francisco.</li><li>We ran a bookmaking workshop for elementary school children with&nbsp;<a href="http://826la.org/">826LA</a>&nbsp;in Venice, CA.</li></ul><p>While GoogleServe is an annual celebration of community service, employees donate both time and money to organizations and causes throughout the year. You can find opportunities to serve your local community at&nbsp;<a href="http://www.allforgood.org/">All For Good</a>.</p>', '<p>Over the last month, more than 7,700 Googlers helped serve their communities across 400 different projects as part of GoogleServe, an employee-driven initiative organized almost entirely by volunteers. Through partnerships with nonprofits, schools and local governments, Googlers from 119 cities in 36 countries helped communities in need with projects ranging from educating youth about online bullying to cleaning up local rivers and parks.&nbsp;<br /><br />GoogleServe began in 2008 and has become an annual company tradition. Giving back to our communities not only revitalizes and strengthens our connections with the cities and towns in which we live and work, it also brings us closer together as a global team. Each year the event has grown in size and scope and this year&rsquo;s GoogleServe was our largest yet. Here&rsquo;s a sampling of some of the projects we participated in this time around:&nbsp;</p><ul><li>In New York, we led resume writing workshops and provided career coaching to&nbsp;<a href="http://iava.org/blog/iava-teams-google-job-skills-workshops">Iraq and Afghanistan Veterans of America</a>&nbsp;members seeking employment.</li><li>We helped&nbsp;<a href="http://www.mountaineering.ie/">Mountaineering Ireland</a>&nbsp;construct drains in order to maintain a stretch of trail along the Dublin Mountains Way.</li><li>We facilitated a strategic planning session for staff from the&nbsp;<a href="http://postprisonedu.org/">Post Prison Education Program</a>&nbsp;in Seattle.</li><li>We conducted an online tools workshop for NGOs in Singapore with the&nbsp;<a href="http://www.nvpc.org.sg/pgm/others/nvpc_f_default_public.aspx">National Volunteer and Philanthropy Centre</a>.</li><li>We fixed up bikes with&nbsp;<a href="http://freeridepgh.org/volunteer/how-to-volunteer/ways-to-volunteer/">Free Ride</a>&nbsp;in Pittsburgh, which will be donated to local nonprofits and residents.</li><li>At the&nbsp;<a href="http://alpha.mapmyindia.com/mcdApp/">Punjabi Bagh Central Market</a>&nbsp;area in West Delhi, we cleaned and removed old, decayed posters with the help of &ldquo;<a href="http://www.letsdoitdelhi.org/">Lets do it Delhi</a>,&rdquo; an organization which has taken up the initiative to minimise abuse of public property.</li><li>We provided one-on-one consultations with high potential, low income women starting their own businesses with the&nbsp;<a href="http://www.womensinitiative.org/index.htm">Women&rsquo;s Initiative for Self Employment</a>&nbsp;in San Francisco.</li><li>We ran a bookmaking workshop for elementary school children with&nbsp;<a href="http://826la.org/">826LA</a>&nbsp;in Venice, CA.</li></ul><p>While GoogleServe is an annual celebration of community service, employees donate both time and money to organizations and causes throughout the year. You can find opportunities to serve your local community at&nbsp;<a href="http://www.allforgood.org/">All For Good</a>.</p>', 6, 0),
(13, 'short', 'tinymce', '<p>Over the last month, more than 7,700 Googlers helped serve their communities across 400 different projects as part of GoogleServe, an employee-driven initiative organized almost entirely by volunteers. Through partnerships with nonprofits, schools and local governments, Googlers from 119 cities in 36 countries helped communities in need with projects ranging from educating youth about online bullying to cleaning up local rivers and parks.&nbsp;</p>', '<p>Over the last month, more than 7,700 Googlers helped serve their communities across 400 different projects as part of GoogleServe, an employee-driven initiative organized almost entirely by volunteers. Through partnerships with nonprofits, schools and local governments, Googlers from 119 cities in 36 countries helped communities in need with projects ranging from educating youth about online bullying to cleaning up local rivers and parks.&nbsp;</p>', 6, 0),
(14, 'body', 'tinymce', '<p>This year, we&rsquo;re mixing up our annual&nbsp;<a href="http://www.zeitgeistminds.com/">Zeitgeist</a>&nbsp;conferences with the launch of Young Minds, a competition hosted by youth engagement agency&nbsp;<a href="http://www.livity.co.uk/">Livity</a>, supported by Google and hosted on YouTube. Starting this week, we&rsquo;re searching for 12 inspirational young people who are making an impact on their world to attend Zeitgeist Americas 2011&mdash;our annual gathering of 400+ businesses and thought leaders from across the continent held each year in Paradise Valley, Ariz.&nbsp;<br /><br />The winners will be invited to the two-day event, where they&rsquo;ll take part in a series of tailored master classes hosted by Google and meet some of the most powerful and thought-provoking people on the planet. We want the pioneers, changemakers and leaders of tomorrow to take their place alongside the greatest minds of today and use Zeitgeist as a springboard from which they can continue to do amazing things to make the world a better place.<br /><br />The Young Minds competition is open to people aged 18-24 from the U.S., Canada, Mexico, Brazil, and Argentina. For your chance to win a slot at Zeitgeist Americas 2011, go to<a href="http://www.zeitgeistyoungminds.com/">www.zeitgeistyoungminds.com</a>&nbsp;before August 25 and upload a video that shows us how you&rsquo;re making a positive impact on the world.<br /><br /><em>Update 7/8:&nbsp;</em>Although previously stated that this competition was open to all of the Americas, please note that it is only open to people from the U.S., Canada, Mexico, Brazil and Argentina.</p>', '<p>This year, we&rsquo;re mixing up our annual&nbsp;<a href="http://www.zeitgeistminds.com/">Zeitgeist</a>&nbsp;conferences with the launch of Young Minds, a competition hosted by youth engagement agency&nbsp;<a href="http://www.livity.co.uk/">Livity</a>, supported by Google and hosted on YouTube. Starting this week, we&rsquo;re searching for 12 inspirational young people who are making an impact on their world to attend Zeitgeist Americas 2011&mdash;our annual gathering of 400+ businesses and thought leaders from across the continent held each year in Paradise Valley, Ariz.&nbsp;<br /><br />The winners will be invited to the two-day event, where they&rsquo;ll take part in a series of tailored master classes hosted by Google and meet some of the most powerful and thought-provoking people on the planet. We want the pioneers, changemakers and leaders of tomorrow to take their place alongside the greatest minds of today and use Zeitgeist as a springboard from which they can continue to do amazing things to make the world a better place.<br /><br />The Young Minds competition is open to people aged 18-24 from the U.S., Canada, Mexico, Brazil, and Argentina. For your chance to win a slot at Zeitgeist Americas 2011, go to<a href="http://www.zeitgeistyoungminds.com/">www.zeitgeistyoungminds.com</a>&nbsp;before August 25 and upload a video that shows us how you&rsquo;re making a positive impact on the world.<br /><br /><em>Update 7/8:&nbsp;</em>Although previously stated that this competition was open to all of the Americas, please note that it is only open to people from the U.S., Canada, Mexico, Brazil and Argentina.</p>', 7, 0),
(15, 'short', 'tinymce', '<p>This year, we&rsquo;re mixing up our annual&nbsp;<a href="http://www.zeitgeistminds.com/">Zeitgeist</a>&nbsp;conferences with the launch of Young Minds, a competition hosted by youth engagement agency&nbsp;<a href="http://www.livity.co.uk/">Livity</a>, supported by Google and hosted on YouTube. Starting this week, we&rsquo;re searching for 12 inspirational young people who are making an impact on their world to attend Zeitgeist Americas 2011&mdash;our annual gathering of 400+ businesses and thought leaders from across the continent held each year in Paradise Valley, Ariz.&nbsp;</p>', '<p>This year, we&rsquo;re mixing up our annual&nbsp;<a href="http://www.zeitgeistminds.com/">Zeitgeist</a>&nbsp;conferences with the launch of Young Minds, a competition hosted by youth engagement agency&nbsp;<a href="http://www.livity.co.uk/">Livity</a>, supported by Google and hosted on YouTube. Starting this week, we&rsquo;re searching for 12 inspirational young people who are making an impact on their world to attend Zeitgeist Americas 2011&mdash;our annual gathering of 400+ businesses and thought leaders from across the continent held each year in Paradise Valley, Ariz.&nbsp;</p>', 7, 0),
(16, 'body', 'tinymce', '<p><strong>Sorry, the page you were looking for in the&nbsp;<a href="/">Flexo demo site</a>&nbsp;does not exist.</strong></p>', '<p><strong>Sorry, the page you were looking for in the&nbsp;<a href="/">Flexo demo site</a>&nbsp;does not exist.</strong></p>', 10, 0),
(17, 'body', '', '', '', 9, 0),
(19, 'body', '', '<?php $archives = $this->archive->get(); ?><div class="archive-items"><?php foreach ($archives as $archive): ?><div class="item"><h3><?php echo $archive->link(); ?></h3><p class="info">Posted by <?php echo $archive->author(); ?> on <?php echo $archive->date(); ?></p></div><?php endforeach; ?></div>', '<?php $archives = $this->archive->get(); ?><div class="archive-items"><?php foreach ($archives as $archive): ?><div class="item"><h3><?php echo $archive->link(); ?></h3><p class="info">Posted by <?php echo $archive->author(); ?> on <?php echo $archive->date(); ?></p></div><?php endforeach; ?></div>', 8, 1),
(20, 'body', '', '', '', 11, 1),
(22, 'body', 'tinymce', '<p>Over the last month, more than 7,700 Googlers helped serve their communities across 400 different projects as part of GoogleServe, an employee-driven initiative organized almost entirely by volunteers. Through partnerships with nonprofits, schools and local governments, Googlers from 119 cities in 36 countries helped communities in need with projects ranging from educating youth about online bullying to cleaning up local rivers and parks.&nbsp;<br /><br />GoogleServe began in 2008 and has become an annual company tradition. Giving back to our communities not only revitalizes and strengthens our connections with the cities and towns in which we live and work, it also brings us closer together as a global team. Each year the event has grown in size and scope and this year&rsquo;s GoogleServe was our largest yet. Here&rsquo;s a sampling of some of the projects we participated in this time around:&nbsp;</p><ul><li>In New York, we led resume writing workshops and provided career coaching to&nbsp;<a href="http://iava.org/blog/iava-teams-google-job-skills-workshops">Iraq and Afghanistan Veterans of America</a>&nbsp;members seeking employment.</li><li>We helped&nbsp;<a href="http://www.mountaineering.ie/">Mountaineering Ireland</a>&nbsp;construct drains in order to maintain a stretch of trail along the Dublin Mountains Way.</li><li>We facilitated a strategic planning session for staff from the&nbsp;<a href="http://postprisonedu.org/">Post Prison Education Program</a>&nbsp;in Seattle.</li><li>We conducted an online tools workshop for NGOs in Singapore with the&nbsp;<a href="http://www.nvpc.org.sg/pgm/others/nvpc_f_default_public.aspx">National Volunteer and Philanthropy Centre</a>.</li><li>We fixed up bikes with&nbsp;<a href="http://freeridepgh.org/volunteer/how-to-volunteer/ways-to-volunteer/">Free Ride</a>&nbsp;in Pittsburgh, which will be donated to local nonprofits and residents.</li><li>At the&nbsp;<a href="http://alpha.mapmyindia.com/mcdApp/">Punjabi Bagh Central Market</a>&nbsp;area in West Delhi, we cleaned and removed old, decayed posters with the help of &ldquo;<a href="http://www.letsdoitdelhi.org/">Lets do it Delhi</a>,&rdquo; an organization which has taken up the initiative to minimise abuse of public property.</li><li>We provided one-on-one consultations with high potential, low income women starting their own businesses with the&nbsp;<a href="http://www.womensinitiative.org/index.htm">Women&rsquo;s Initiative for Self Employment</a>&nbsp;in San Francisco.</li><li>We ran a bookmaking workshop for elementary school children with&nbsp;<a href="http://826la.org/">826LA</a>&nbsp;in Venice, CA.</li></ul><p>While GoogleServe is an annual celebration of community service, employees donate both time and money to organizations and causes throughout the year. You can find opportunities to serve your local community at&nbsp;<a href="http://www.allforgood.org/">All For Good</a>.</p>', '<p>Over the last month, more than 7,700 Googlers helped serve their communities across 400 different projects as part of GoogleServe, an employee-driven initiative organized almost entirely by volunteers. Through partnerships with nonprofits, schools and local governments, Googlers from 119 cities in 36 countries helped communities in need with projects ranging from educating youth about online bullying to cleaning up local rivers and parks.&nbsp;<br /><br />GoogleServe began in 2008 and has become an annual company tradition. Giving back to our communities not only revitalizes and strengthens our connections with the cities and towns in which we live and work, it also brings us closer together as a global team. Each year the event has grown in size and scope and this year&rsquo;s GoogleServe was our largest yet. Here&rsquo;s a sampling of some of the projects we participated in this time around:&nbsp;</p><ul><li>In New York, we led resume writing workshops and provided career coaching to&nbsp;<a href="http://iava.org/blog/iava-teams-google-job-skills-workshops">Iraq and Afghanistan Veterans of America</a>&nbsp;members seeking employment.</li><li>We helped&nbsp;<a href="http://www.mountaineering.ie/">Mountaineering Ireland</a>&nbsp;construct drains in order to maintain a stretch of trail along the Dublin Mountains Way.</li><li>We facilitated a strategic planning session for staff from the&nbsp;<a href="http://postprisonedu.org/">Post Prison Education Program</a>&nbsp;in Seattle.</li><li>We conducted an online tools workshop for NGOs in Singapore with the&nbsp;<a href="http://www.nvpc.org.sg/pgm/others/nvpc_f_default_public.aspx">National Volunteer and Philanthropy Centre</a>.</li><li>We fixed up bikes with&nbsp;<a href="http://freeridepgh.org/volunteer/how-to-volunteer/ways-to-volunteer/">Free Ride</a>&nbsp;in Pittsburgh, which will be donated to local nonprofits and residents.</li><li>At the&nbsp;<a href="http://alpha.mapmyindia.com/mcdApp/">Punjabi Bagh Central Market</a>&nbsp;area in West Delhi, we cleaned and removed old, decayed posters with the help of &ldquo;<a href="http://www.letsdoitdelhi.org/">Lets do it Delhi</a>,&rdquo; an organization which has taken up the initiative to minimise abuse of public property.</li><li>We provided one-on-one consultations with high potential, low income women starting their own businesses with the&nbsp;<a href="http://www.womensinitiative.org/index.htm">Women&rsquo;s Initiative for Self Employment</a>&nbsp;in San Francisco.</li><li>We ran a bookmaking workshop for elementary school children with&nbsp;<a href="http://826la.org/">826LA</a>&nbsp;in Venice, CA.</li></ul><p>While GoogleServe is an annual celebration of community service, employees donate both time and money to organizations and causes throughout the year. You can find opportunities to serve your local community at&nbsp;<a href="http://www.allforgood.org/">All For Good</a>.</p>', 15, 0),
(23, 'short', 'tinymce', '<p>Over the last month, more than 7,700 Googlers helped serve their communities across 400 different projects as part of GoogleServe, an employee-driven initiative organized almost entirely by volunteers. Through partnerships with nonprofits, schools and local governments, Googlers from 119 cities in 36 countries helped communities in need with projects ranging from educating youth about online bullying to cleaning up local rivers and parks.&nbsp;</p>', '<p>Over the last month, more than 7,700 Googlers helped serve their communities across 400 different projects as part of GoogleServe, an employee-driven initiative organized almost entirely by volunteers. Through partnerships with nonprofits, schools and local governments, Googlers from 119 cities in 36 countries helped communities in need with projects ranging from educating youth about online bullying to cleaning up local rivers and parks.&nbsp;</p>', 15, 0);
INSERT INTO `page_part` (`id`, `name`, `filter_id`, `content`, `content_html`, `page_id`, `is_protected`) VALUES
(24, 'body', 'tinymce', '<p>At Google, we&rsquo;re committed to using technology to solve one of the greatest challenges we face as a country: building a clean energy future. That&rsquo;s why we&rsquo;ve worked hard to be carbon neutral as a company, launched our&nbsp;<a href="http://www.google.org/rec.html">renewable energy cheaper than coal</a>&nbsp;initiative and have<a href="http://www.google.com/green/collaborations/support-innovations.html">invested</a>&nbsp;in several clean energy companies and projects around the world.&nbsp;<br /><br />But what if we knew the value of innovation in clean energy technologies? How much could new technologies contribute to our economic growth, enhance our energy security or reduce greenhouse gas (GHG) emissions? Robust data can help us understand these important questions, and the role innovation in clean energy could play in addressing our future economic, security and climate challenges.<br /><br />Through Google.org, our energy team set out to answer some of these questions. Using<a href="http://www.mckinsey.com/clientservice/sustainability/low_carbon_economics_tool.asp">McKinsey&rsquo;s Low Carbon Economics Tool</a>&nbsp;(LCET), we assessed the long-term economic impacts for the U.S. assuming breakthroughs were made in several different clean energy technologies, like wind, geothermal and electric vehicles. McKinsey&rsquo;s LCET is a neutral, analytic set of interlinked models that estimates the potential economic and technology implications of various policy and technology assumptions.&nbsp;<br /><br />The analysis is based on a model and includes assumptions and conclusions that Google.org developed, so it isn&rsquo;t a prediction of the future. We&rsquo;ve decided to make the&nbsp;<a href="http://www.google.org/energyinnovation">analysis and associated data</a>&nbsp;available everywhere because we believe it could provide a new perspective on the economic value of public and private investment in energy innovation. Here are just some of the most compelling findings:&nbsp;</p><ul><li><strong>Energy innovation pays off big:</strong>&nbsp;We compared &ldquo;business as usual&rdquo; (BAU) to scenarios with breakthroughs in clean energy technologies. On top of those, we layered a series of possible clean energy policies (more details in the&nbsp;<a href="http://www.google.org/energyinnovation">report</a>). We found that by 2030, when compared to BAU, breakthroughs could help the U.S.:</li><ul><li>Grow GDP by over $155 billion/year ($244 billion in our Clean Policy scenario)</li><li>Create over 1.1 million new full-time jobs/year (1.9 million with Clean Policy)</li><li>Reduce household energy costs by over $942/year ($995 with Clean Policy)</li><li>Reduce U.S. oil consumption by over 1.1 billion barrels/year</li><li>Reduce U.S. total carbon emissions by 13% in 2030 (21% with Clean Policy)</li></ul><li><strong>Speed matters and delay is costly:</strong>&nbsp;Our model found a mere five year delay (2010-2015) in accelerating technology innovation led to $2.3-3.2 trillion in unrealized GDP, an aggregate 1.2-1.4 million net unrealized jobs and 8-28 more gigatons of potential GHG emissions by 2050.</li><li><strong>Policy and innovation can enhance each other:</strong>&nbsp;Combining clean energy policies with technological breakthroughs increased the economic, security and pollution benefits for either innovation or policy alone. Take GHG emissions: the model showed that combining policy and innovation led to 59% GHG reductions by 2050 (vs. 2005 levels), while maintaining economic growth.</li></ul><p>This analysis assumed that breakthroughs in clean energy happened and that policies were put in place, and then tried to understand the impact. The data here allows us to imagine a world in which the U.S. captures the potential benefits of some clean energy technologies: economic growth, job generation and a reduction in harmful emissions. We haven&rsquo;t developed the roadmap, and getting there will take the right mix of policies, sustained investment in technological innovation by public and private institutions and mobilization of the private sector&rsquo;s entrepreneurial energies. We hope this analysis encourages further discussion and debate on these important issues.</p>', '<p>At Google, we&rsquo;re committed to using technology to solve one of the greatest challenges we face as a country: building a clean energy future. That&rsquo;s why we&rsquo;ve worked hard to be carbon neutral as a company, launched our&nbsp;<a href="http://www.google.org/rec.html">renewable energy cheaper than coal</a>&nbsp;initiative and have<a href="http://www.google.com/green/collaborations/support-innovations.html">invested</a>&nbsp;in several clean energy companies and projects around the world.&nbsp;<br /><br />But what if we knew the value of innovation in clean energy technologies? How much could new technologies contribute to our economic growth, enhance our energy security or reduce greenhouse gas (GHG) emissions? Robust data can help us understand these important questions, and the role innovation in clean energy could play in addressing our future economic, security and climate challenges.<br /><br />Through Google.org, our energy team set out to answer some of these questions. Using<a href="http://www.mckinsey.com/clientservice/sustainability/low_carbon_economics_tool.asp">McKinsey&rsquo;s Low Carbon Economics Tool</a>&nbsp;(LCET), we assessed the long-term economic impacts for the U.S. assuming breakthroughs were made in several different clean energy technologies, like wind, geothermal and electric vehicles. McKinsey&rsquo;s LCET is a neutral, analytic set of interlinked models that estimates the potential economic and technology implications of various policy and technology assumptions.&nbsp;<br /><br />The analysis is based on a model and includes assumptions and conclusions that Google.org developed, so it isn&rsquo;t a prediction of the future. We&rsquo;ve decided to make the&nbsp;<a href="http://www.google.org/energyinnovation">analysis and associated data</a>&nbsp;available everywhere because we believe it could provide a new perspective on the economic value of public and private investment in energy innovation. Here are just some of the most compelling findings:&nbsp;</p><ul><li><strong>Energy innovation pays off big:</strong>&nbsp;We compared &ldquo;business as usual&rdquo; (BAU) to scenarios with breakthroughs in clean energy technologies. On top of those, we layered a series of possible clean energy policies (more details in the&nbsp;<a href="http://www.google.org/energyinnovation">report</a>). We found that by 2030, when compared to BAU, breakthroughs could help the U.S.:</li><ul><li>Grow GDP by over $155 billion/year ($244 billion in our Clean Policy scenario)</li><li>Create over 1.1 million new full-time jobs/year (1.9 million with Clean Policy)</li><li>Reduce household energy costs by over $942/year ($995 with Clean Policy)</li><li>Reduce U.S. oil consumption by over 1.1 billion barrels/year</li><li>Reduce U.S. total carbon emissions by 13% in 2030 (21% with Clean Policy)</li></ul><li><strong>Speed matters and delay is costly:</strong>&nbsp;Our model found a mere five year delay (2010-2015) in accelerating technology innovation led to $2.3-3.2 trillion in unrealized GDP, an aggregate 1.2-1.4 million net unrealized jobs and 8-28 more gigatons of potential GHG emissions by 2050.</li><li><strong>Policy and innovation can enhance each other:</strong>&nbsp;Combining clean energy policies with technological breakthroughs increased the economic, security and pollution benefits for either innovation or policy alone. Take GHG emissions: the model showed that combining policy and innovation led to 59% GHG reductions by 2050 (vs. 2005 levels), while maintaining economic growth.</li></ul><p>This analysis assumed that breakthroughs in clean energy happened and that policies were put in place, and then tried to understand the impact. The data here allows us to imagine a world in which the U.S. captures the potential benefits of some clean energy technologies: economic growth, job generation and a reduction in harmful emissions. We haven&rsquo;t developed the roadmap, and getting there will take the right mix of policies, sustained investment in technological innovation by public and private institutions and mobilization of the private sector&rsquo;s entrepreneurial energies. We hope this analysis encourages further discussion and debate on these important issues.</p>', 16, 0),
(25, 'short', 'tinymce', '<p>At Google, we&rsquo;re committed to using technology to solve one of the greatest challenges we face as a country: building a clean energy future. That&rsquo;s why we&rsquo;ve worked hard to be carbon neutral as a company, launched our&nbsp;<a href="http://www.google.org/rec.html">renewable energy cheaper than coal</a>&nbsp;initiative and have<a href="http://www.google.com/green/collaborations/support-innovations.html">invested</a>&nbsp;in several clean energy companies and projects around the world.&nbsp;</p>', '<p>At Google, we&rsquo;re committed to using technology to solve one of the greatest challenges we face as a country: building a clean energy future. That&rsquo;s why we&rsquo;ve worked hard to be carbon neutral as a company, launched our&nbsp;<a href="http://www.google.org/rec.html">renewable energy cheaper than coal</a>&nbsp;initiative and have<a href="http://www.google.com/green/collaborations/support-innovations.html">invested</a>&nbsp;in several clean energy companies and projects around the world.&nbsp;</p>', 16, 0),
(27, 'short', 'tinymce', '<p>More than a thousand Googlers participated in Pride celebrations in a dozen cities to support equality and remember the sacrifices of those who have made life better for members of the LGBT* community today. While we celebrated the&nbsp;<a href="http://www.prideagenda.org/Issues-Explained/Marriage-and-Family-Protection/NY-Business-for-Marriage.aspx">legalization</a>&nbsp;of marriage equality in New York, the state where the gay rights movement in the United States began more than 40 years ago, our participation was especially global this year: we were at&nbsp;<a href="http://google-au.blogspot.com/2011/03/google-joins-2011-sydney-gay-lesbian.html">Mardi Gras in Sydney</a>, Australia for the first time and&nbsp;<a href="http://pinkdotsg.blogspot.com/2011/06/pink-dot-2011-now-supported-by-google.html">supported Pink Dot in Singapore</a>. From San Francisco to Dublin to Tel Aviv to Boston, we stepped out in large numbers for Pride parades around the world in a colorful swirl of Gaygler and Android Pride t-shirts. As in years past, we featured a month-long easter egg in our search results worldwide to celebrate Pride, adding a rainbow next to the search box for a number of Pride-related queries including [<a href="http://www.google.com/search?q=lgbt">lgbt</a>], [<a href="http://www.google.com/search?q=marriage+equality">marriage equality</a>] and [<a href="http://www.google.com/search?q=pride+2011">pride 2011</a>].</p>', '<p>More than a thousand Googlers participated in Pride celebrations in a dozen cities to support equality and remember the sacrifices of those who have made life better for members of the LGBT* community today. While we celebrated the&nbsp;<a href="http://www.prideagenda.org/Issues-Explained/Marriage-and-Family-Protection/NY-Business-for-Marriage.aspx">legalization</a>&nbsp;of marriage equality in New York, the state where the gay rights movement in the United States began more than 40 years ago, our participation was especially global this year: we were at&nbsp;<a href="http://google-au.blogspot.com/2011/03/google-joins-2011-sydney-gay-lesbian.html">Mardi Gras in Sydney</a>, Australia for the first time and&nbsp;<a href="http://pinkdotsg.blogspot.com/2011/06/pink-dot-2011-now-supported-by-google.html">supported Pink Dot in Singapore</a>. From San Francisco to Dublin to Tel Aviv to Boston, we stepped out in large numbers for Pride parades around the world in a colorful swirl of Gaygler and Android Pride t-shirts. As in years past, we featured a month-long easter egg in our search results worldwide to celebrate Pride, adding a rainbow next to the search box for a number of Pride-related queries including [<a href="http://www.google.com/search?q=lgbt">lgbt</a>], [<a href="http://www.google.com/search?q=marriage+equality">marriage equality</a>] and [<a href="http://www.google.com/search?q=pride+2011">pride 2011</a>].</p>', 17, 0),
(28, 'body', 'tinymce', '<p>This year, we&rsquo;re mixing up our annual&nbsp;<a href="http://www.zeitgeistminds.com/">Zeitgeist</a>&nbsp;conferences with the launch of Young Minds, a competition hosted by youth engagement agency&nbsp;<a href="http://www.livity.co.uk/">Livity</a>, supported by Google and hosted on YouTube. Starting this week, we&rsquo;re searching for 12 inspirational young people who are making an impact on their world to attend Zeitgeist Americas 2011&mdash;our annual gathering of 400+ businesses and thought leaders from across the continent held each year in Paradise Valley, Ariz.&nbsp;<br /><br />The winners will be invited to the two-day event, where they&rsquo;ll take part in a series of tailored master classes hosted by Google and meet some of the most powerful and thought-provoking people on the planet. We want the pioneers, changemakers and leaders of tomorrow to take their place alongside the greatest minds of today and use Zeitgeist as a springboard from which they can continue to do amazing things to make the world a better place.<br /><br />The Young Minds competition is open to people aged 18-24 from the U.S., Canada, Mexico, Brazil, and Argentina. For your chance to win a slot at Zeitgeist Americas 2011, go to<a href="http://www.zeitgeistyoungminds.com/">www.zeitgeistyoungminds.com</a>&nbsp;before August 25 and upload a video that shows us how you&rsquo;re making a positive impact on the world.<br /><br /><em>Update 7/8:&nbsp;</em>Although previously stated that this competition was open to all of the Americas, please note that it is only open to people from the U.S., Canada, Mexico, Brazil and Argentina.</p>', '<p>This year, we&rsquo;re mixing up our annual&nbsp;<a href="http://www.zeitgeistminds.com/">Zeitgeist</a>&nbsp;conferences with the launch of Young Minds, a competition hosted by youth engagement agency&nbsp;<a href="http://www.livity.co.uk/">Livity</a>, supported by Google and hosted on YouTube. Starting this week, we&rsquo;re searching for 12 inspirational young people who are making an impact on their world to attend Zeitgeist Americas 2011&mdash;our annual gathering of 400+ businesses and thought leaders from across the continent held each year in Paradise Valley, Ariz.&nbsp;<br /><br />The winners will be invited to the two-day event, where they&rsquo;ll take part in a series of tailored master classes hosted by Google and meet some of the most powerful and thought-provoking people on the planet. We want the pioneers, changemakers and leaders of tomorrow to take their place alongside the greatest minds of today and use Zeitgeist as a springboard from which they can continue to do amazing things to make the world a better place.<br /><br />The Young Minds competition is open to people aged 18-24 from the U.S., Canada, Mexico, Brazil, and Argentina. For your chance to win a slot at Zeitgeist Americas 2011, go to<a href="http://www.zeitgeistyoungminds.com/">www.zeitgeistyoungminds.com</a>&nbsp;before August 25 and upload a video that shows us how you&rsquo;re making a positive impact on the world.<br /><br /><em>Update 7/8:&nbsp;</em>Although previously stated that this competition was open to all of the Americas, please note that it is only open to people from the U.S., Canada, Mexico, Brazil and Argentina.</p>', 18, 0),
(29, 'short', 'tinymce', '<p>This year, we&rsquo;re mixing up our annual&nbsp;<a href="http://www.zeitgeistminds.com/">Zeitgeist</a>&nbsp;conferences with the launch of Young Minds, a competition hosted by youth engagement agency&nbsp;<a href="http://www.livity.co.uk/">Livity</a>, supported by Google and hosted on YouTube. Starting this week, we&rsquo;re searching for 12 inspirational young people who are making an impact on their world to attend Zeitgeist Americas 2011&mdash;our annual gathering of 400+ businesses and thought leaders from across the continent held each year in Paradise Valley, Ariz.&nbsp;</p>', '<p>This year, we&rsquo;re mixing up our annual&nbsp;<a href="http://www.zeitgeistminds.com/">Zeitgeist</a>&nbsp;conferences with the launch of Young Minds, a competition hosted by youth engagement agency&nbsp;<a href="http://www.livity.co.uk/">Livity</a>, supported by Google and hosted on YouTube. Starting this week, we&rsquo;re searching for 12 inspirational young people who are making an impact on their world to attend Zeitgeist Americas 2011&mdash;our annual gathering of 400+ businesses and thought leaders from across the continent held each year in Paradise Valley, Ariz.&nbsp;</p>', 18, 0),
(30, 'body', 'tinymce', '<p>We recently held an Innovation Workshop for the 2011&nbsp;<a href="http://googleblog.blogspot.com/2011/02/making-sense-of-science-introducing.html">Google Science Communication Fellows</a>, a group of early to mid-career PhD scientists chosen for their leadership in climate change research and communication. The Fellows spent three days together alongside Googlers and external experts at the Googleplex in Mountain View, Calif. exploring the potential of information technology and social media to spur public engagement.&nbsp;<br /><br />All 21 of the&nbsp;<a href="http://www.google.com/edu/science/fellows.html">2011 Fellows</a>&nbsp;are experienced science communicators, trained in using traditional media to bridge the gap between complex science and the general public. This workshop was an opportunity for them to explore new media communications optimized for the age of the web; or, as as I like to say, learning how to &ldquo;web&rdquo; the gap between the science community and the larger world in the digital age.&nbsp;<br />We organized the workshop around three themes:</p><ol><li><strong>Understanding the public.</strong>&nbsp;This session introduced trending tools&mdash; like search,<a href="http://www.google.com/trends">Google Trends</a>&nbsp;and&nbsp;<a href="http://correlate.googlelabs.com/">Correlate</a>&mdash;that can be used to gather data from search queries and online discussions. If you&rsquo;re curious,&nbsp;<a href="http://www.youtube.com/watch?v=63lgyeJnRJI">watch</a>&nbsp;Google user experience researcher, Dan Russel, give the Fellows a 101 on how people search, and what they&rsquo;re looking for.</li><li><strong>Documenting your science story.</strong>&nbsp;Here, the Fellows played around with&nbsp;<a href="http://www.google.com/earth/index.html">Google Earth</a>,&nbsp;<a href="http://www.google.com/fusiontables/public/tour/index.html">Fusion Tables</a>&nbsp;and&nbsp;<a href="http://www.youtube.com/">YouTube</a>&nbsp;to learn how to create interactive and engaging stories with science data, which could then be shared with a broad audience. For more on this, visit the&nbsp;<a href="http://www.youtube.com/watch?v=lZ7PJOwSh8k">Science Communications Fellows talks page</a>&nbsp;on YouTube.</li><li><strong>Joining the conversation.</strong>&nbsp;In this session, Googler Chris Messina, a developer advocate, took the Fellows on a journey into the social web, illustrating by examples the power of the crowd in shaping ideas and building understanding across diverse social networks. You can view Chris&rsquo;s outstanding talk&nbsp;<a href="http://www.youtube.com/watch?v=IrTSiO9ejOs">here</a>.</li></ol><p>Several external experts participated in the workshop as well, including Andy Revkin,&nbsp;<a href="http://dotearth.blogs.nytimes.com/">Dot Earth</a>&nbsp;blogger and senior fellow of environmental understanding at Pace University. Andy gave a thought-provoking&nbsp;<a href="http://www.youtube.com/watch?v=lU_4OR3hOyo">keynote</a>&nbsp;the first evening, which also included a self-composed ditty about the fossil age (look out&nbsp;<em>Schoolhouse Rock!</em>).<br /><br />Armed with new knowledge on &ldquo;webbing the gap,&rdquo; the Fellows are now developing project proposals to put what they learned into practice. Proposal selections will be made later this summer. You can learn more about tools for science communication in the digital age and the innovation workshop at our site&nbsp;<a href="http://www.google.com/edu/science/">here</a>. Stay tuned for future opportunities for participating in this program.</p>', '<p>We recently held an Innovation Workshop for the 2011&nbsp;<a href="http://googleblog.blogspot.com/2011/02/making-sense-of-science-introducing.html">Google Science Communication Fellows</a>, a group of early to mid-career PhD scientists chosen for their leadership in climate change research and communication. The Fellows spent three days together alongside Googlers and external experts at the Googleplex in Mountain View, Calif. exploring the potential of information technology and social media to spur public engagement.&nbsp;<br /><br />All 21 of the&nbsp;<a href="http://www.google.com/edu/science/fellows.html">2011 Fellows</a>&nbsp;are experienced science communicators, trained in using traditional media to bridge the gap between complex science and the general public. This workshop was an opportunity for them to explore new media communications optimized for the age of the web; or, as as I like to say, learning how to &ldquo;web&rdquo; the gap between the science community and the larger world in the digital age.&nbsp;<br />We organized the workshop around three themes:</p><ol><li><strong>Understanding the public.</strong>&nbsp;This session introduced trending tools&mdash; like search,<a href="http://www.google.com/trends">Google Trends</a>&nbsp;and&nbsp;<a href="http://correlate.googlelabs.com/">Correlate</a>&mdash;that can be used to gather data from search queries and online discussions. If you&rsquo;re curious,&nbsp;<a href="http://www.youtube.com/watch?v=63lgyeJnRJI">watch</a>&nbsp;Google user experience researcher, Dan Russel, give the Fellows a 101 on how people search, and what they&rsquo;re looking for.</li><li><strong>Documenting your science story.</strong>&nbsp;Here, the Fellows played around with&nbsp;<a href="http://www.google.com/earth/index.html">Google Earth</a>,&nbsp;<a href="http://www.google.com/fusiontables/public/tour/index.html">Fusion Tables</a>&nbsp;and&nbsp;<a href="http://www.youtube.com/">YouTube</a>&nbsp;to learn how to create interactive and engaging stories with science data, which could then be shared with a broad audience. For more on this, visit the&nbsp;<a href="http://www.youtube.com/watch?v=lZ7PJOwSh8k">Science Communications Fellows talks page</a>&nbsp;on YouTube.</li><li><strong>Joining the conversation.</strong>&nbsp;In this session, Googler Chris Messina, a developer advocate, took the Fellows on a journey into the social web, illustrating by examples the power of the crowd in shaping ideas and building understanding across diverse social networks. You can view Chris&rsquo;s outstanding talk&nbsp;<a href="http://www.youtube.com/watch?v=IrTSiO9ejOs">here</a>.</li></ol><p>Several external experts participated in the workshop as well, including Andy Revkin,&nbsp;<a href="http://dotearth.blogs.nytimes.com/">Dot Earth</a>&nbsp;blogger and senior fellow of environmental understanding at Pace University. Andy gave a thought-provoking&nbsp;<a href="http://www.youtube.com/watch?v=lU_4OR3hOyo">keynote</a>&nbsp;the first evening, which also included a self-composed ditty about the fossil age (look out&nbsp;<em>Schoolhouse Rock!</em>).<br /><br />Armed with new knowledge on &ldquo;webbing the gap,&rdquo; the Fellows are now developing project proposals to put what they learned into practice. Proposal selections will be made later this summer. You can learn more about tools for science communication in the digital age and the innovation workshop at our site&nbsp;<a href="http://www.google.com/edu/science/">here</a>. Stay tuned for future opportunities for participating in this program.</p>', 19, 0),
(31, 'short', 'tinymce', '<p>We recently held an Innovation Workshop for the 2011&nbsp;<a href="http://googleblog.blogspot.com/2011/02/making-sense-of-science-introducing.html">Google Science Communication Fellows</a>, a group of early to mid-career PhD scientists chosen for their leadership in climate change research and communication. The Fellows spent three days together alongside Googlers and external experts at the Googleplex in Mountain View, Calif. exploring the potential of information technology and social media to spur public engagement.&nbsp;</p>', '<p>We recently held an Innovation Workshop for the 2011&nbsp;<a href="http://googleblog.blogspot.com/2011/02/making-sense-of-science-introducing.html">Google Science Communication Fellows</a>, a group of early to mid-career PhD scientists chosen for their leadership in climate change research and communication. The Fellows spent three days together alongside Googlers and external experts at the Googleplex in Mountain View, Calif. exploring the potential of information technology and social media to spur public engagement.&nbsp;</p>', 19, 0),
(33, 'body', '', '<?php $archives = $this->archive->get(); ?><div class="archive-items"><?php foreach ($archives as $archive): ?><div class="item"><h3><?php echo $archive->link(); ?></h3><p class="info">Posted by <?php echo $archive->author(); ?> on <?php echo $archive->date(); ?></p></div><?php endforeach; ?></div>', '<?php $archives = $this->archive->get(); ?><div class="archive-items"><?php foreach ($archives as $archive): ?><div class="item"><h3><?php echo $archive->link(); ?></h3><p class="info">Posted by <?php echo $archive->author(); ?> on <?php echo $archive->date(); ?></p></div><?php endforeach; ?></div>', 20, 1),
(34, 'body', '', '<?php if(isset($this->pages) AND !empty($this->pages)): ?><h1>Результаты поиска</h1><hr /><?php foreach($this->pages as $page): ?><?php if (isset($page) AND !empty($page)): ?><div class="item"><?php print_r( $page->link()); ?></div><?php endif; ?><?php endforeach; ?><?php else: ?><h1>По вашему запросу ничего не найдено</h1><?php endif; ?>', '<?php if(isset($this->pages) AND !empty($this->pages)): ?><h1>Результаты поиска</h1><hr /><?php foreach($this->pages as $page): ?><?php if (isset($page) AND !empty($page)): ?><div class="item"><?php print_r( $page->link()); ?></div><?php endif; ?><?php endforeach; ?><?php else: ?><h1>По вашему запросу ничего не найдено</h1><?php endif; ?>', 21, 1),
(36, 'body', '', '<?php if($events): ?><div id="QAItems"><?php foreach ($events as $q): ?><div class="item"><div class="question round"><?php echo $q->link(); ?><div class="date"><?php echo $q->date(); ?></div></div></div><?php endforeach; ?></div><?php else: ?><h3>Не задано ни одного вопроса</h3><?php endif; ?>', '<?php if($events): ?><div id="QAItems"><?php foreach ($events as $q): ?><div class="item"><div class="question round"><?php echo $q->link(); ?><div class="date"><?php echo $q->date(); ?></div></div></div><?php endforeach; ?></div><?php else: ?><h3>Не задано ни одного вопроса</h3><?php endif; ?>', 23, 1),
(37, 'body', '', '<div class="text"><?php echo $question->question; ?></div><div class="answer round"><div class="name"><h2>Ответ</h2></div><div class="text"><?php echo $question->answer; ?></div></div>', '<div class="text"><?php echo $question->question; ?></div><div class="answer round"><div class="name"><h2>Ответ</h2></div><div class="text"><?php echo $question->answer; ?></div></div>', 24, 0);

-- --------------------------------------------------------

--
-- Table structure for table `page_permission`
--

CREATE TABLE `page_permission` (
  `page_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `page_permission`
--

INSERT INTO `page_permission` (`page_id`, `permission_id`) VALUES
(1, 1),
(2, 1),
(8, 1),
(3, 1),
(23, 2),
(23, 1),
(21, 1),
(23, 3),
(24, 1),
(24, 2),
(24, 3);

-- --------------------------------------------------------

--
-- Table structure for table `page_tag`
--

CREATE TABLE `page_tag` (
  `page_id` int(11) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `page_id` (`page_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `page_tag`
--

INSERT INTO `page_tag` (`page_id`, `tag_id`) VALUES
(3, 1),
(21, 0);

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `name`) VALUES
(1, 'administrator'),
(2, 'developer'),
(3, 'editor');

-- --------------------------------------------------------

--
-- Table structure for table `pf_field`
--

CREATE TABLE `pf_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pi_image`
--

CREATE TABLE `pi_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `file_name` varchar(256) NOT NULL,
  `created_date` datetime NOT NULL,
  `description` text,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_photos`
--

CREATE TABLE `plugin_photos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `position` (`position`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_photos_categories`
--

CREATE TABLE `plugin_photos_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `plugin_photos_categories`
--

INSERT INTO `plugin_photos_categories` (`id`, `position`, `title`) VALUES
(1, 0, 'dfsdf');

-- --------------------------------------------------------

--
-- Table structure for table `plugin_settings`
--

CREATE TABLE `plugin_settings` (
  `plugin_id` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `value` varchar(255) NOT NULL,
  UNIQUE KEY `plugin_setting_id` (`plugin_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `plugin_settings`
--

INSERT INTO `plugin_settings` (`plugin_id`, `name`, `value`) VALUES
('search', 'search_query', 'q'),
('search', 'search_only_title', 'yes'),
('captcha', 'at_login_form', 'yes'),
('cache', 'cache_dynamic', 'no'),
('cache', 'cache_static', 'yes'),
('cache', 'cache_remove_static', 'no'),
('cache', 'cache_lifetime', '86400'),
('cache', 'folder_path', 'public/less'),
('search', 'search_query_key', 'q'),
('cache', 'enabled', 'yes'),
('less', 'enabled', 'yes'),
('less', 'folder_path', 'public'),
('less', 'less_folder_path', 'public/less'),
('less', 'css_folder_path', 'public/css'),
('less', 'format_css', 'yes'),
('qa', 'layout_file', 'normal'),
('qa', 'page_id', '23'),
('qa', 'page_slug', 'about-project'),
('qa', 'use_pager', 'yes'),
('yandex_metrika', 'yandex_metrika_id', '234234'),
('events', 'page_slug', 'qa'),
('events', 'use_pager', 'no'),
('image_resizing', 'cache_sizes', ''),
('image_resizing', 'quality', '90'),
('redirect', 'domain', 'flexo'),
('redirect', 'check_url_suffix', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `qa`
--

CREATE TABLE `qa` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(2) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `expert` varchar(100) DEFAULT 'эксперт компании I-P-G',
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `locale` (`expert`,`date_created`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `qa`
--

INSERT INTO `qa` (`id`, `status`, `title`, `email`, `phone`, `name`, `question`, `answer`, `expert`, `date_created`, `date_updated`) VALUES
(27, 2, 'erw', 'wer', 'werw', 'test', 'erwer', '<p>erwerwer</p>', 'эксперт компании I-P-G', '2012-08-20 12:54:08', '2012-08-20 12:54:16');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `name` varchar(40) NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `id` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`name`, `value`) VALUES
('admin_title', 'ButscH CMS'),
('theme', 'special'),
('default_status_id', '1'),
('default_filter_id', ''),
('default_tab', '/admin/page'),
('allow_html_title', 'off'),
('plugins', 'a:13:{s:7:"textile";i:1;s:8:"markdown";i:1;s:12:"file_manager";i:1;s:13:"files_manager";i:1;s:6:"search";i:1;s:7:"archive";i:1;s:7:"sitemap";i:1;s:8:"redirect";i:1;s:6:"photos";i:1;s:6:"events";i:1;s:14:"page_not_found";i:1;s:5:"cache";i:1;s:6:"backup";i:1;}'),
('profiling', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `count` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`id`, `name`, `count`) VALUES
(1, 'wer', 1),
(2, 'sdf sdf sdf', 1),
(3, 'fdsdfsdf', 1),
(4, 'dsfsd f', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) DEFAULT NULL,
  `language` varchar(5) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `last_login` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `username`, `password`, `language`, `created_on`, `updated_on`, `created_by_id`, `updated_by_id`, `last_login`) VALUES
(1, 'ButscH', 'butschster@gmail.com', 'admin', 'b4bf77705c10942f75ef8bb546d0c508d119df9d', 'ru', '2012-08-15 11:02:59', '2012-08-15 11:03:00', 1, 1, '2012-09-05 16:14:10');

-- --------------------------------------------------------

--
-- Table structure for table `user_permission`
--

CREATE TABLE `user_permission` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  UNIQUE KEY `user_id` (`user_id`,`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_permission`
--

INSERT INTO `user_permission` (`user_id`, `permission_id`) VALUES
(1, 1);

