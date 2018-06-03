-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 24-Maio-2018 às 00:59
-- Versão do servidor: 5.7.21
-- PHP Version: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id2375570_financas`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `comprovantes`
--

DROP TABLE IF EXISTS `comprovantes`;
CREATE TABLE IF NOT EXISTS `comprovantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comp` mediumblob NOT NULL,
  `nome` varchar(150) CHARACTER SET utf8 NOT NULL,
  `tipo` varchar(150) CHARACTER SET utf8 NOT NULL,
  `ext` varchar(15) CHARACTER SET utf8 NOT NULL,
  `tamanho` varchar(15) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `exclusoes`
--

DROP TABLE IF EXISTS `exclusoes`;
CREATE TABLE IF NOT EXISTS `exclusoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mov_exc` int(11) NOT NULL,
  `tipo_mov` int(11) NOT NULL,
  `valor_mov` decimal(12,2) NOT NULL,
  `cat_mov` int(11) NOT NULL,
  `conta_mov` int(11) NOT NULL,
  `data_exc` date NOT NULL,
  `desc_mov` longtext NOT NULL,
  `usuario_mov` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico`
--

DROP TABLE IF EXISTS `historico`;
CREATE TABLE IF NOT EXISTS `historico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mov` int(11) NOT NULL,
  `just_id` int(11) NOT NULL,
  `conta_mov` int(11) NOT NULL,
  `data` date NOT NULL,
  `usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `just_ed`
--

DROP TABLE IF EXISTS `just_ed`;
CREATE TABLE IF NOT EXISTS `just_ed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `just` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `just_ed`
--

INSERT INTO `just_ed` (`id`, `just`) VALUES
(1, 'Dia.'),
(2, 'Mês.'),
(3, 'Ano.'),
(4, 'Tipo.'),
(5, 'Categoria.'),
(6, 'Descrição.'),
(7, 'Valor.'),
(8, 'Conta.'),
(9, 'Comprovante.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `movimentos`
--

DROP TABLE IF EXISTS `movimentos`;
CREATE TABLE IF NOT EXISTS `movimentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dia` int(2) UNSIGNED ZEROFILL DEFAULT NULL,
  `mes` int(2) UNSIGNED ZEROFILL DEFAULT NULL,
  `ano` int(4) DEFAULT NULL,
  `tipo` int(1) DEFAULT NULL,
  `valor` decimal(12,2) DEFAULT NULL,
  `nparcela` int(2) DEFAULT NULL,
  `parcelas` int(2) DEFAULT NULL,
  `cat` int(11) DEFAULT NULL,
  `conta` int(1) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `descricao` longtext,
  `edicao` longtext,
  `comp_img` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `orcamento`
--

DROP TABLE IF EXISTS `orcamento`;
CREATE TABLE IF NOT EXISTS `orcamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mes` int(2) NOT NULL,
  `ano` int(4) NOT NULL,
  `valor` decimal(12,2) NOT NULL,
  `conta` int(1) NOT NULL,
  `usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `sobrenome` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(150) NOT NULL,
  `data` date NOT NULL,
  `ultimavisita` datetime DEFAULT NULL,
  `n_acesso_f` int(11) NOT NULL DEFAULT '0',
  `visa` decimal(12,2) NOT NULL DEFAULT '0.00',
  `dia_venc_v` int(11) DEFAULT NULL,
  `master` decimal(12,2) NOT NULL DEFAULT '0.00',
  `dia_venc_m` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
