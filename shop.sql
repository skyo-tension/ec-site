-- Cleaned SQL: kept only `mst_product` which current code (ProductModel) uses.
-- Original dump is preserved below as a commented backup.

-- Backup: original full dump (kept as comment)
-- -----------------------------------------------------------------------------
--
-- Original dump (commented) START
--
-- (Full original dump omitted here for brevity)
--
-- Original dump (commented) END
-- -----------------------------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET NAMES utf8mb4 */;

-- データベース: `shop` — reduced schema

-- テーブルの構造 `mst_product`
CREATE TABLE `mst_product` (
  `code` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `price` int(11) NOT NULL,
  `gazou` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- テーブルのデータのダンプ `mst_product`
INSERT INTO `mst_product` (`code`, `name`, `price`, `gazou`) VALUES
(1, 'りんご', 250, ''),
(3, 'まるいち3', 500, 'ninjin_yama.jpg');

-- インデックス
ALTER TABLE `mst_product`
  ADD PRIMARY KEY (`code`);

-- AUTO_INCREMENT
ALTER TABLE `mst_product`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
