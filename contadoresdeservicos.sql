-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 24-Abr-2014 às 14:58
-- Versão do servidor: 5.6.13
-- versão do PHP: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `contadoresdeservicos`
--
CREATE DATABASE IF NOT EXISTS `contadoresdeservicos` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `contadoresdeservicos`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contadores`
--

CREATE TABLE IF NOT EXISTS `contadores` (
  `id_contador` int(11) NOT NULL AUTO_INCREMENT,
  `id_servico` int(11) NOT NULL,
  `situacao` varchar(45) NOT NULL,
  `consumo` float DEFAULT NULL,
  `data_instalacao` date DEFAULT NULL,
  PRIMARY KEY (`id_contador`),
  KEY `id_servico_idx` (`id_servico`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

--
-- Extraindo dados da tabela `contadores`
--

INSERT INTO `contadores` (`id_contador`, `id_servico`, `situacao`, `consumo`, `data_instalacao`) VALUES
(1, 1, 'ligado', 4534, '2014-04-08'),
(2, 2, 'desligado', 3543, '2014-04-08'),
(3, 3, 'ligado', 8764, '2014-04-07'),
(19, 2, 'ligado', 12345, '2014-02-03'),
(22, 1, 'ligado', 507234, '2011-02-12'),
(23, 3, 'desligado', 7, '2012-12-12'),
(24, 3, 'contagem', 1234, '2012-12-12'),
(25, 1, 'contagem', 12345, '2011-11-11'),
(29, 2, 'desligado', 0, '2001-10-01'),
(30, 1, 'avariado', 46362, '2012-02-11'),
(31, 1, 'ligado', 51973, '2001-02-03'),
(32, 3, 'ligado', 147852000, '2003-02-01'),
(33, 3, 'ligado', 123654, '2001-02-03'),
(34, 1, 'desligado', 123, '2012-12-12'),
(36, 3, 'ligado', 12345, '2004-05-06'),
(38, 1, 'ligado', 123456, '2004-05-06'),
(39, 1, 'ligado', 15937, '2001-02-01'),
(40, 1, 'ligado', 1593720, '2001-02-01'),
(41, 3, 'ligado', 987654, '2009-07-03'),
(42, 1, 'ligado', 78606, '2006-04-05'),
(45, 1, 'ligado', 56146, '2014-04-10');

-- --------------------------------------------------------

--
-- Estrutura da tabela `favorito`
--

CREATE TABLE IF NOT EXISTS `favorito` (
  `id_utilizador` int(11) NOT NULL,
  `id_ponto` int(11) NOT NULL,
  KEY `id_ponto_idx` (`id_ponto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `favorito`
--

INSERT INTO `favorito` (`id_utilizador`, `id_ponto`) VALUES
(2, 1),
(2, 3),
(2, 14),
(1, 1),
(1, 3),
(1, 25),
(1, 24),
(2, 25),
(2, 27),
(2, 26),
(2, 36),
(2, 35),
(2, 37),
(2, 31),
(1, 37),
(2, 33);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pontos`
--

CREATE TABLE IF NOT EXISTS `pontos` (
  `id_ponto` int(11) NOT NULL AUTO_INCREMENT,
  `id_contador` int(11) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `descricao` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_ponto`),
  KEY `id_contador_idx` (`id_contador`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- Extraindo dados da tabela `pontos`
--

INSERT INTO `pontos` (`id_ponto`, `id_contador`, `latitude`, `longitude`, `descricao`) VALUES
(1, 1, -8.84674, 41.69413, 'ESTG'),
(2, 2, -8.8462, 41.69303, 'Piscina Municipal do Atlantico'),
(3, 3, -8.84547, 41.69331, 'Pavilhao Desportivo Municipal de Monserrate'),
(14, 19, -8.84841369842532, 41.69319267226184, 'Scala'),
(17, 22, -8.842856161346436, 41.69318466085453, 'Pavilhao ESM'),
(18, 23, -8.842083685150136, 41.692736020466796, '12345'),
(19, 24, -8.840849869003293, 41.693072501051134, 'Cantina ESM'),
(20, 25, -8.840710394134593, 41.692695963136686, 'Casa Ze'),
(24, 29, -8.845173589935335, 41.692896249535764, 'Desconhecido'),
(25, 30, -8.843446247329771, 41.69268795166821, 'Varandas do Sol'),
(26, 31, -8.834766618957515, 41.692063054017645, 'IPVC'),
(27, 32, -8.834745161285428, 41.69193086335177, 'IPVC'),
(28, 33, -8.835528366317726, 41.69192685756968, 'Sandes Jardim'),
(29, 34, -8.837003581275885, 41.691910834438794, 'Chafariz'),
(31, 36, -8.839004509201118, 41.69071709999693, 'ETAP'),
(33, 38, -8.835037636909531, 41.691654750871635, 'Diver'),
(34, 39, -8.839111797561642, 41.6908532990063, 'ETAP'),
(35, 40, -8.830196134796159, 41.69014826572514, 'Gil Eanes'),
(36, 41, -8.8303999826813, 41.69005212422451, 'Gil Eanes'),
(37, 42, -8.836247198333762, 41.690989497726626, 'Casa Seixas'),
(40, 45, -8.838811390152065, 41.69413400564496, 'Cantina ESTG');

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos`
--

CREATE TABLE IF NOT EXISTS `servicos` (
  `id_servico` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `contacto` varchar(45) NOT NULL,
  PRIMARY KEY (`id_servico`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `servicos`
--

INSERT INTO `servicos` (`id_servico`, `nome`, `contacto`) VALUES
(1, 'Agua', 'agua@gmail.com'),
(2, 'Luz', 'luz@gmail.com'),
(3, 'Gas', 'gas@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE IF NOT EXISTS `utilizadores` (
  `id_utilizador` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  PRIMARY KEY (`id_utilizador`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizador`, `email`, `password`, `tipo`) VALUES
(1, 'duartecostamendes@gmail.com', 'superadmin', 'admin'),
(2, 'luis@dias.com', 'luisinho', 'normal'),
(12, 'code@igniter.com', 'codeig', 'normal'),
(18, 'yo@yeah.com', 'yeah', 'normal'),
(19, 'yeah@yo.com', 'yo', 'normal');

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadoresservicos`
--

CREATE TABLE IF NOT EXISTS `utilizadoresservicos` (
  `id_utilizador` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL,
  KEY `id_servico_idx` (`id_servico`),
  KEY `id_utilizador_idx` (`id_utilizador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `utilizadoresservicos`
--

INSERT INTO `utilizadoresservicos` (`id_utilizador`, `id_servico`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 3);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `contadores`
--
ALTER TABLE `contadores`
  ADD CONSTRAINT `id_servico` FOREIGN KEY (`id_servico`) REFERENCES `servicos` (`id_servico`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `favorito`
--
ALTER TABLE `favorito`
  ADD CONSTRAINT `id_ponto` FOREIGN KEY (`id_ponto`) REFERENCES `pontos` (`id_ponto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `pontos`
--
ALTER TABLE `pontos`
  ADD CONSTRAINT `id_contador` FOREIGN KEY (`id_contador`) REFERENCES `contadores` (`id_contador`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
