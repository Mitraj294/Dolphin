-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 06, 2025 at 07:03 AM
-- Server version: 8.0.43-0ubuntu0.24.04.2
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dolphin`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessment_results`
--

CREATE TABLE `assessment_results` (
  `id` bigint UNSIGNED NOT NULL,
  `organization_assessment_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` enum('original','adjust') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'original',
  `self_a` double DEFAULT NULL,
  `conc_a` double DEFAULT NULL,
  `task_a` double DEFAULT NULL,
  `adj_a` double DEFAULT NULL,
  `self_b` double DEFAULT NULL,
  `conc_b` double DEFAULT NULL,
  `task_b` double DEFAULT NULL,
  `adj_b` double DEFAULT NULL,
  `self_c` double DEFAULT NULL,
  `conc_c` double DEFAULT NULL,
  `task_c` double DEFAULT NULL,
  `adj_c` double DEFAULT NULL,
  `self_d` double DEFAULT NULL,
  `conc_d` double DEFAULT NULL,
  `task_d` double DEFAULT NULL,
  `adj_d` double DEFAULT NULL,
  `self_avg` double DEFAULT NULL,
  `conc_avg` double DEFAULT NULL,
  `adj_avg` double DEFAULT NULL,
  `task_avg` double DEFAULT NULL,
  `dec_approach` double DEFAULT NULL,
  `self_total_count` int DEFAULT NULL,
  `conc_total_count` int DEFAULT NULL,
  `adj_total_count` int DEFAULT NULL,
  `self_total_words` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `conc_total_words` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `adj_total_words` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `assessment_results`
--

INSERT INTO `assessment_results` (`id`, `organization_assessment_id`, `user_id`, `type`, `self_a`, `conc_a`, `task_a`, `adj_a`, `self_b`, `conc_b`, `task_b`, `adj_b`, `self_c`, `conc_c`, `task_c`, `adj_c`, `self_d`, `conc_d`, `task_d`, `adj_d`, `self_avg`, `conc_avg`, `adj_avg`, `task_avg`, `dec_approach`, `self_total_count`, `conc_total_count`, `adj_total_count`, `self_total_words`, `conc_total_words`, `adj_total_words`, `created_at`, `updated_at`) VALUES
(1, NULL, 10, 'original', 0.1513158, 0.1152381, 0.20614596670935, 0, 0.2014134, 0.0736469, 0, 0.27014218009479, 0.1287263, 0, 0, 0.17025089605735, 0, 0.2292419, 0, 0, 0.1203639, 0.1045317, 0.1100983, 0.0515365, 0.1203639, 7, 9, 5, '[\"Individualistic\",\"Assertive\",\"Persuasive\",\"Optimistic\",\"Charismatic\",\"Relaxed\",\"Stable\"]', '[\"Timid\",\"Unassuming\",\"Agreeable\",\"Sophisticated\",\"Formal\",\"Respectful\",\"Organized\",\"Persistent\",\"Responsive\"]', '[\"Persuasive\",\"Optimistic\",\"Charismatic\",\"Relaxed\",\"Stable\"]', '2025-10-07 04:20:27', '2025-10-09 04:20:27'),
(2, NULL, 10, 'adjust', 0.1137218, 0.1533333, 0, 0.42756183745583, 0.0733216, 0.202307, 0, 0.098341232227488, 0, 0.1559934, 0, 0, 0.2271914, 0, 0.26587578506629, 0, 0.1035587, 0.1279084, 0.1314758, 0.0664689, 0.1035587, 9, 7, 4, '[\"Timid\",\"Unassuming\",\"Agreeable\",\"Sophisticated\",\"Formal\",\"Respectful\",\"Organized\",\"Persistent\",\"Responsive\"]', '[\"Individualistic\",\"Assertive\",\"Persuasive\",\"Charismatic\",\"Optimistic\",\"Relaxed\",\"Stable\"]', '[\"Timid\",\"Unassuming\",\"Agreeable\",\"Sophisticated\"]', '2025-10-08 04:21:13', '2025-10-09 04:21:13'),
(3, NULL, 10, 'adjust', 0.8176692, 0.4628571, 0.90653008962868, 0.57243816254417, 0.914311, 0.4924579, 0.95138888888889, 0.90165876777251, 1, 0.681445, 1, 1, 0.6714371, 0.4554753, 0.62526168876483, 0.94262295081967, 0.8508543, 0.5230588, 0.85418, 0.8707952, 0.8508543, 80, 54, 37, '[\"Individualistic\",\"Assertive\",\"Audacious\",\"Meek\",\"Humble\",\"Competitive\",\"Docile\",\"Independent\",\"Confident\",\"Harmonious\",\"Resourceful\",\"Daring\",\"Submissive\",\"Accommodating\",\"Demanding\",\"Optimistic\",\"Persuasive\",\"Charismatic\",\"Captivating\",\"Serious\",\"Outgoing\",\"Reserved\",\"Friendly\",\"Quiet\",\"Gregarious\",\"Enthusiastic\",\"Sociable\",\"Constrained\",\"Silent\",\"Thoughtful\",\"Reflective\",\"Stimulating\",\"Introspective\",\"Logical\",\"Shy\",\"Relaxed\",\"Stable\",\"Patient\",\"Methodical\",\"Volatile\",\"Passive\",\"Fast-Paced\",\"Deliberate\",\"Intense\",\"Restless\",\"Driving\",\"High-Strung\",\"Calm\",\"Tranquil\",\"Tense\",\"Consistent\",\"Forceful\",\"Quick\",\"Cautious\",\"Thorough\",\"Frank\",\"Arbitrary\",\"Disorganized\",\"Unrestrained\",\"Dutiful\",\"Careful\",\"Undisciplined\",\"Rebellious\",\"Conventional\",\"Polite\",\"Unconventional\",\"Loyal\",\"Flexible\",\"Unstructured\",\"Accurate\",\"Informal\",\"Casual\",\"Neat\",\"Meticulous\",\"Disciplined\",\"Inflexible\",\"Conscientious\",\"Uninhibited\",\"Fussy\",\"Precise\"]', '[\"Humble\",\"Confident\",\"Resourceful\",\"Independent\",\"Competitive\",\"Harmonious\",\"Meek\",\"Docile\",\"Submissive\",\"Daring\",\"Friendly\",\"Reserved\",\"Quiet\",\"Gregarious\",\"Silent\",\"Sociable\",\"Constrained\",\"Enthusiastic\",\"Sophisticated\",\"Introspective\",\"Logical\",\"Shy\",\"Driving\",\"Restless\",\"Fast-Paced\",\"Volatile\",\"Passive\",\"Deliberate\",\"Intense\",\"High-Strung\",\"Calm\",\"Quick\",\"Consistent\",\"Dutiful\",\"Unrestrained\",\"Careful\",\"Undisciplined\",\"Disorganized\",\"Arbitrary\",\"Frank\",\"Thorough\",\"Unconventional\",\"Flexible\",\"Loyal\",\"Conventional\",\"Rebellious\",\"Polite\",\"Accurate\",\"Neat\",\"Unstructured\",\"Respectful\",\"Precise\",\"Conscientious\",\"Uninhibited\"]', '[\"Meek\",\"Humble\",\"Docile\",\"Harmonious\",\"Submissive\",\"Accommodating\",\"Optimistic\",\"Persuasive\",\"Charismatic\",\"Captivating\",\"Outgoing\",\"Friendly\",\"Gregarious\",\"Enthusiastic\",\"Sociable\",\"Stimulating\",\"Relaxed\",\"Stable\",\"Patient\",\"Methodical\",\"Passive\",\"Deliberate\",\"Calm\",\"Tranquil\",\"Consistent\",\"Frank\",\"Arbitrary\",\"Disorganized\",\"Unrestrained\",\"Undisciplined\",\"Rebellious\",\"Unconventional\",\"Flexible\",\"Unstructured\",\"Informal\",\"Casual\",\"Uninhibited\"]', '2025-10-09 04:52:45', '2025-10-09 04:52:45'),
(4, NULL, 10, 'adjust', 0.0780075, 0.0742857, 0.10627400768246, 0, 0.119258, 0, 0, 0.15995260663507, 0.0582656, 0.0706076, 0, 0.077060931899642, 0, 0, 0, 0, 0.0638828, 0.0362233, 0.0592534, 0.0265685, 0.0638828, 4, 2, 3, '[\"Individualistic\",\"Persuasive\",\"Optimistic\",\"Relaxed\"]', '[\"Assertive\",\"Relaxed\"]', '[\"Persuasive\",\"Optimistic\",\"Relaxed\"]', '2025-10-10 05:43:56', '2025-10-09 05:43:56'),
(5, NULL, 10, 'adjust', 0.1033835, 0.6066667, 0.10627400768246, 0.095406360424028, 0.0803887, 0.4498669, 0.31597222222222, 0, 0.1205962, 0.6059113, 0.25555555555556, 0.077060931899642, 0.2230173, 0.2797834, 0.2470341939986, 0.081967213114754, 0.1318464, 0.4855571, 0.0636086, 0.231209, 0.1318464, 14, 41, 3, '[\"Individualistic\",\"Humble\",\"Constrained\",\"Introspective\",\"Reserved\",\"Relaxed\",\"Restless\",\"High-Strung\",\"Inflexible\",\"Fussy\",\"Formal\",\"Respectful\",\"Unconventional\",\"Careful\"]', '[\"Individualistic\",\"Meek\",\"Humble\",\"Confident\",\"Timid\",\"Accommodating\",\"Submissive\",\"Harmonious\",\"Assertive\",\"Audacious\",\"Independent\",\"Daring\",\"Friendly\",\"Reflective\",\"Constrained\",\"Introspective\",\"Silent\",\"Reserved\",\"Persuasive\",\"Captivating\",\"Serious\",\"Quiet\",\"Enthusiastic\",\"Relaxed\",\"Restless\",\"High-Strung\",\"Passive\",\"Volatile\",\"Fast-Paced\",\"Tranquil\",\"Consistent\",\"Quick\",\"Meticulous\",\"Fussy\",\"Persistent\",\"Disciplined\",\"Unstructured\",\"Careful\",\"Frank\",\"Casual\",\"Uninhibited\"]', '[\"Humble\",\"Relaxed\",\"Unconventional\"]', '2025-10-11 05:45:02', '2025-10-09 05:45:02'),
(6, NULL, 10, 'adjust', 0.1691729, 0.2542857, 0.19590268886044, 0.095406360424028, 0.2959364, 0.1614907, 0.076388888888889, 0.37085308056872, 0.2249322, 0.183908, 0.12777777777778, 0.25627240143369, 0.1091234, 0.2527076, 0.060711793440335, 0.39344262295082, 0.1997912, 0.213098, 0.2789936, 0.1151953, 0.1997912, 20, 21, 13, '[\"Audacious\",\"Humble\",\"Independent\",\"Persuasive\",\"Enthusiastic\",\"Constrained\",\"Stimulating\",\"Shy\",\"Sophisticated\",\"Relaxed\",\"Stable\",\"Patient\",\"Volatile\",\"Driving\",\"Disorganized\",\"Unrestrained\",\"Undisciplined\",\"Flexible\",\"Meticulous\",\"Uninhibited\"]', '[\"Competitive\",\"Independent\",\"Submissive\",\"Accommodating\",\"Demanding\",\"Friendly\",\"Serious\",\"Silent\",\"Stimulating\",\"Patient\",\"Tense\",\"Quick\",\"Unrestrained\",\"Careful\",\"Cautious\",\"Rebellious\",\"Unconventional\",\"Loyal\",\"Precise\",\"Respectful\",\"Responsive\"]', '[\"Humble\",\"Persuasive\",\"Enthusiastic\",\"Stimulating\",\"Sophisticated\",\"Relaxed\",\"Stable\",\"Patient\",\"Disorganized\",\"Unrestrained\",\"Undisciplined\",\"Flexible\",\"Uninhibited\"]', '2025-10-12 06:37:13', '2025-10-09 06:37:13'),
(7, NULL, 10, 'adjust', 0.143797, 0.0828571, 0.19590268886044, 0, 0.2146643, 0, 0.3125, 0.18127962085308, 0.0582656, 0.1986864, 0, 0.077060931899642, 0.0238521, 0.0529483, 0.02791346824843, 0, 0.1101448, 0.083623, 0.0645851, 0.134079, 0.1101448, 8, 7, 3, '[\"Audacious\",\"Independent\",\"Optimistic\",\"Serious\",\"Reserved\",\"Sophisticated\",\"Relaxed\",\"Dutiful\"]', '[\"Resourceful\",\"Relaxed\",\"Volatile\",\"Deliberate\",\"Cautious\",\"Undisciplined\",\"Unconventional\"]', '[\"Optimistic\",\"Sophisticated\",\"Relaxed\"]', '2025-10-13 07:05:24', '2025-10-09 07:05:24'),
(9, 1, 30, 'adjust', 0, 0.0742857, 0, 0, 0.0574205, 0.0576752, 0, 0.077014218009479, 0.1287263, 0.1559934, 0, 0.17025089605735, 0, 0, 0, 0, 0.0465367, 0.0719886, 0.0618163, 0, 0.0465367, 3, 4, 3, '[\"Persuasive\",\"Relaxed\",\"Stable\"]', '[\"Assertive\",\"Persuasive\",\"Relaxed\",\"Stable\"]', '[\"Persuasive\",\"Relaxed\",\"Stable\"]', '2025-10-16 06:48:09', '2025-10-16 06:48:09'),
(10, 3, 30, 'adjust', 0.0733083, 0.0866667, 0.099871959026889, 0, 0.119258, 0.0354925, 0, 0.15995260663507, 0.1287263, 0.3119869, 0, 0.17025089605735, 0, 0.0860409, 0, 0, 0.0803232, 0.1300468, 0.0825509, 0.024968, 0.0803232, 5, 8, 4, '[\"Assertive\",\"Persuasive\",\"Optimistic\",\"Relaxed\",\"Stable\"]', '[\"Audacious\",\"Serious\",\"Methodical\",\"Patient\",\"Tranquil\",\"Cautious\",\"Casual\",\"Neat\"]', '[\"Persuasive\",\"Optimistic\",\"Relaxed\",\"Stable\"]', '2025-10-16 06:48:46', '2025-10-16 06:48:46'),
(11, 5, 38, 'original', 0.3007519, 0.3666667, 0.40204865556978, 0.021201413427562, 0.2155477, 0.1393079, 0.069444444444444, 0.2654028436019, 0.2235772, 0.1773399, 0.12222222222222, 0.25627240143369, 0.0763268, 0.0619735, 0.043265875785066, 0.27049180327869, 0.2040509, 0.186322, 0.2033421, 0.1592453, 0.2040509, 18, 14, 11, '[\"Individualistic\",\"Assertive\",\"Audacious\",\"Independent\",\"Submissive\",\"Persuasive\",\"Optimistic\",\"Gregarious\",\"Logical\",\"Relaxed\",\"Stable\",\"Patient\",\"Fast-Paced\",\"Cautious\",\"Arbitrary\",\"Disorganized\",\"Unstructured\",\"Uninhibited\"]', '[\"Assertive\",\"Audacious\",\"Competitive\",\"Independent\",\"Resourceful\",\"Optimistic\",\"Enthusiastic\",\"Relaxed\",\"Methodical\",\"Volatile\",\"Frank\",\"Unrestrained\",\"Loyal\",\"Unconventional\"]', '[\"Submissive\",\"Persuasive\",\"Optimistic\",\"Gregarious\",\"Relaxed\",\"Stable\",\"Patient\",\"Arbitrary\",\"Disorganized\",\"Unstructured\",\"Uninhibited\"]', '2025-10-16 07:07:43', '2025-10-16 07:07:43'),
(13, 6, 38, 'adjust', 0.4774436, 0.5914286, 0.37131882202305, 0.77031802120141, 0.6722615, 0.595386, 0.36458333333333, 0.77725118483412, 0.4457995, 0.9310345, 0.58333333333333, 0.40143369175627, 0.5909362, 0.5890493, 0.65806001395673, 0.19672131147541, 0.5466102, 0.6767246, 0.5364311, 0.4943239, 0.5466102, 52, 56, 22, '[\"Assertive\",\"Competitive\",\"Confident\",\"Resourceful\",\"Harmonious\",\"Submissive\",\"Accommodating\",\"Timid\",\"Unassuming\",\"Agreeable\",\"Persuasive\",\"Optimistic\",\"Captivating\",\"Charismatic\",\"Reserved\",\"Gregarious\",\"Enthusiastic\",\"Constrained\",\"Silent\",\"Reflective\",\"Stimulating\",\"Shy\",\"Sophisticated\",\"Relaxed\",\"Stable\",\"Methodical\",\"Passive\",\"Volatile\",\"Fast-Paced\",\"Intense\",\"Driving\",\"High-Strung\",\"Tense\",\"Forceful\",\"Cautious\",\"Arbitrary\",\"Disorganized\",\"Careful\",\"Rebellious\",\"Conventional\",\"Accurate\",\"Casual\",\"Meticulous\",\"Neat\",\"Disciplined\",\"Inflexible\",\"Conscientious\",\"Formal\",\"Persistent\",\"Precise\",\"Fussy\",\"Respectful\"]', '[\"Assertive\",\"Meek\",\"Competitive\",\"Docile\",\"Harmonious\",\"Resourceful\",\"Daring\",\"Demanding\",\"Timid\",\"Unassuming\",\"Agreeable\",\"Persuasive\",\"Optimistic\",\"Charismatic\",\"Friendly\",\"Outgoing\",\"Sociable\",\"Enthusiastic\",\"Constrained\",\"Thoughtful\",\"Stimulating\",\"Logical\",\"Introspective\",\"Relaxed\",\"Stable\",\"Methodical\",\"Deliberate\",\"Volatile\",\"Patient\",\"Intense\",\"Restless\",\"Driving\",\"High-Strung\",\"Calm\",\"Tranquil\",\"Tense\",\"Quick\",\"Cautious\",\"Disorganized\",\"Unrestrained\",\"Frank\",\"Dutiful\",\"Undisciplined\",\"Conventional\",\"Polite\",\"Accurate\",\"Flexible\",\"Unstructured\",\"Inflexible\",\"Meticulous\",\"Uninhibited\",\"Formal\",\"Persistent\",\"Respectful\",\"Organized\",\"Responsive\"]', '[\"Harmonious\",\"Submissive\",\"Accommodating\",\"Timid\",\"Unassuming\",\"Agreeable\",\"Persuasive\",\"Optimistic\",\"Captivating\",\"Charismatic\",\"Gregarious\",\"Enthusiastic\",\"Stimulating\",\"Sophisticated\",\"Relaxed\",\"Stable\",\"Methodical\",\"Passive\",\"Arbitrary\",\"Disorganized\",\"Rebellious\",\"Casual\"]', '2025-10-17 05:40:09', '2025-10-17 05:40:09'),
(14, 8, 42, 'original', 0.4389098, 0.3380952, 0.41869398207426, 0.49469964664311, 0.2924028, 0.621118, 0.36805555555556, 0.26658767772512, 0.5921409, 0.8292282, 0.78888888888889, 0.52867383512545, 0.4454383, 0.4482551, 0.48220516399163, 0.22950819672131, 0.442223, 0.5591741, 0.3798673, 0.5144609, 0.442223, 43, 47, 17, '[\"Assertive\",\"Meek\",\"Humble\",\"Docile\",\"Competitive\",\"Daring\",\"Accommodating\",\"Demanding\",\"Timid\",\"Persuasive\",\"Outgoing\",\"Quiet\",\"Enthusiastic\",\"Constrained\",\"Introspective\",\"Logical\",\"Relaxed\",\"Stable\",\"Methodical\",\"Volatile\",\"Fast-Paced\",\"Intense\",\"Restless\",\"Driving\",\"High-Strung\",\"Calm\",\"Tranquil\",\"Quick\",\"Disorganized\",\"Arbitrary\",\"Thorough\",\"Undisciplined\",\"Careful\",\"Dutiful\",\"Conventional\",\"Accurate\",\"Neat\",\"Meticulous\",\"Flexible\",\"Inflexible\",\"Precise\",\"Formal\",\"Persistent\"]', '[\"Audacious\",\"Assertive\",\"Meek\",\"Humble\",\"Confident\",\"Harmonious\",\"Unassuming\",\"Persuasive\",\"Charismatic\",\"Optimistic\",\"Captivating\",\"Outgoing\",\"Silent\",\"Thoughtful\",\"Constrained\",\"Enthusiastic\",\"Stimulating\",\"Shy\",\"Sophisticated\",\"Relaxed\",\"Stable\",\"Methodical\",\"Fast-Paced\",\"Intense\",\"Driving\",\"Calm\",\"High-Strung\",\"Tense\",\"Consistent\",\"Tranquil\",\"Forceful\",\"Quick\",\"Thorough\",\"Arbitrary\",\"Disorganized\",\"Polite\",\"Careful\",\"Flexible\",\"Casual\",\"Loyal\",\"Conventional\",\"Precise\",\"Disciplined\",\"Conscientious\",\"Persistent\",\"Responsive\",\"Formal\"]', '[\"Meek\",\"Humble\",\"Docile\",\"Accommodating\",\"Timid\",\"Persuasive\",\"Outgoing\",\"Enthusiastic\",\"Relaxed\",\"Stable\",\"Methodical\",\"Calm\",\"Tranquil\",\"Disorganized\",\"Arbitrary\",\"Undisciplined\",\"Flexible\"]', '2025-10-17 05:47:18', '2025-10-17 05:47:18'),
(15, 7, 38, 'adjust', 0.4830827, 0.3066667, 0.49295774647887, 0.45583038869258, 0.3012367, 0.3682343, 0.36805555555556, 0.27843601895735, 0.4457995, 0.4696223, 0.53333333333333, 0.41756272401434, 0.5110316, 0.354994, 0.56943475226797, 0.16803278688525, 0.4352876, 0.3748793, 0.3299655, 0.4909453, 0.4352876, 41, 32, 13, '[\"Assertive\",\"Humble\",\"Daring\",\"Confident\",\"Competitive\",\"Docile\",\"Demanding\",\"Accommodating\",\"Unassuming\",\"Persuasive\",\"Enthusiastic\",\"Quiet\",\"Constrained\",\"Introspective\",\"Logical\",\"Sophisticated\",\"Relaxed\",\"Stable\",\"Volatile\",\"Fast-Paced\",\"Intense\",\"Driving\",\"Calm\",\"High-Strung\",\"Tense\",\"Tranquil\",\"Cautious\",\"Dutiful\",\"Undisciplined\",\"Polite\",\"Careful\",\"Conventional\",\"Loyal\",\"Flexible\",\"Neat\",\"Meticulous\",\"Disciplined\",\"Inflexible\",\"Conscientious\",\"Fussy\",\"Organized\"]', '[\"Assertive\",\"Audacious\",\"Meek\",\"Harmonious\",\"Demanding\",\"Captivating\",\"Persuasive\",\"Optimistic\",\"Outgoing\",\"Enthusiastic\",\"Thoughtful\",\"Relaxed\",\"Methodical\",\"Stable\",\"Intense\",\"Restless\",\"High-Strung\",\"Calm\",\"Forceful\",\"Cautious\",\"Arbitrary\",\"Disorganized\",\"Dutiful\",\"Polite\",\"Conventional\",\"Accurate\",\"Flexible\",\"Casual\",\"Informal\",\"Inflexible\",\"Conscientious\",\"Persistent\"]', '[\"Humble\",\"Docile\",\"Accommodating\",\"Unassuming\",\"Persuasive\",\"Enthusiastic\",\"Sophisticated\",\"Relaxed\",\"Stable\",\"Calm\",\"Tranquil\",\"Undisciplined\",\"Flexible\"]', '2025-10-30 01:29:39', '2025-10-30 01:29:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessment_results`
--
ALTER TABLE `assessment_results`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessment_results`
--
ALTER TABLE `assessment_results`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
