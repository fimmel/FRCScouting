--
-- Database: `scout`
--
CREATE DATABASE IF NOT EXISTS `scout` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `scout`;

-- --------------------------------------------------------

--
-- Table structure for table `2020_Match`
--

CREATE TABLE `2020_Match` (
  `ID` int(11) NOT NULL COMMENT 'Auto Inc ID Number',
  `BM_ID` int(11) NOT NULL COMMENT 'What Bot/Match',
  `Sub` int(11) NOT NULL COMMENT 'Submission ID',
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of entry',
  `sd_cw_rotation` int(11) NOT NULL,
  `sd_cw_position` int(11) NOT NULL,
  `sd_eg_hang` int(11) NOT NULL,
  `sd_eg_hang_level` int(11) NOT NULL,
  `sd_eg_hang_bots` int(11) NOT NULL,
  `sd_def_giving_rating` int(11) NOT NULL,
  `sd_def_receiving_rating` int(11) NOT NULL,
  `sd_def_notes` mediumtext NOT NULL,
  `sd_match_notes` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `2020_Pit`
--

CREATE TABLE `2020_Pit` (
  `ID` int(11) NOT NULL COMMENT 'Auto Inc ID Number',
  `BM_ID` int(11) NOT NULL COMMENT 'What Bot/Match',
  `Scout` int(11) NOT NULL COMMENT 'Who Scouted',
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of entry',
  `CP_Rot_Attempt` int(11) NOT NULL,
  `CP_Rot_Complete` int(11) NOT NULL,
  `CP_Pos_Attempt` int(11) NOT NULL,
  `CP_Pos_Complete` int(11) NOT NULL,
  `EG_Hang_Attempt` int(11) NOT NULL,
  `EG_Hang_Complete` int(11) NOT NULL,
  `EG_Hang_Level` int(11) NOT NULL,
  `EG_Hang_Robots` int(11) NOT NULL,
  `EG_Park` int(11) NOT NULL,
  `Def_Played` int(11) NOT NULL,
  `Def_Rating` int(11) NOT NULL,
  `Notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `2020_Shots`
--

CREATE TABLE `2020_Shots` (
  `ID` int(11) NOT NULL COMMENT 'Auto Inc ID Number',
  `BM_ID` int(11) NOT NULL COMMENT 'What Bot/Match',
  `Sub` int(11) NOT NULL COMMENT 'Submission ID',
  `Array_Position` int(11) NOT NULL,
  `period` int(11) NOT NULL,
  `Time` bigint(20) NOT NULL,
  `Action` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `2020_Submission`
--

CREATE TABLE `2020_Submission` (
  `ID` int(11) NOT NULL,
  `BM_ID` int(11) NOT NULL,
  `Scout` int(11) NOT NULL,
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `2020_TBA`
--

CREATE TABLE `2020_TBA` (
  `ID` int(11) NOT NULL,
  `match_ID` int(11) NOT NULL,
  `TBAkey` varchar(20) NOT NULL,
  `played` int(11) NOT NULL,
  `time` bigint(20) NOT NULL,
  `color` varchar(11) NOT NULL,
  `autoPoints` int(11) NOT NULL,
  `autoCellsOuter` int(11) NOT NULL,
  `stage3TargetColor` varchar(255) NOT NULL,
  `controlPanelPoints` int(11) NOT NULL,
  `foulCount` int(11) NOT NULL,
  `teleopCellsOuter` int(11) NOT NULL,
  `foulPoints` int(11) NOT NULL,
  `techFoulCount` int(11) NOT NULL,
  `rp` int(11) NOT NULL,
  `adjustPoints` int(11) NOT NULL,
  `stage2Activated` int(11) NOT NULL,
  `initLineRobot2` varchar(20) NOT NULL,
  `initLineRobot3` varchar(20) NOT NULL,
  `autoCellsBottom` int(11) NOT NULL,
  `initLineRobot1` varchar(20) NOT NULL,
  `teleopCellsBottom` int(11) NOT NULL,
  `stage3Activated` int(11) NOT NULL,
  `shieldEnergizedRankingPoint` int(11) NOT NULL,
  `shieldOperationalRankingPoint` int(11) NOT NULL,
  `endgameRungIsLevel` varchar(20) NOT NULL,
  `endgameRobot1` varchar(20) NOT NULL,
  `autoInitLinePoints` int(11) NOT NULL,
  `endgameRobot3` varchar(20) NOT NULL,
  `totalPoints` int(11) NOT NULL,
  `teleopCellPoints` int(11) NOT NULL,
  `tba_shieldEnergizedRankingPointFromFoul` int(11) NOT NULL,
  `teleopCellsInner` int(11) NOT NULL,
  `endgameRobot2` varchar(20) NOT NULL,
  `endgamePoints` int(11) NOT NULL,
  `stage1Activated` int(11) NOT NULL,
  `autoCellsInner` int(11) NOT NULL,
  `autoCellPoints` int(11) NOT NULL,
  `teleopPoints` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `tba_key` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `abbr` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='1 Row for each event';

-- --------------------------------------------------------

--
-- Table structure for table `event_teams`
--

CREATE TABLE `event_teams` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `IP` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `page` varchar(255) NOT NULL,
  `function` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `level` int(11) NOT NULL COMMENT '1=Prac, 2=Qual, 3=QF, 4=SF, 5=F',
  `set_num` int(11) NOT NULL,
  `match_num` int(11) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `match_robot`
--

CREATE TABLE `match_robot` (
  `id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL COMMENT 'team number',
  `position` int(11) NOT NULL COMMENT '1=r1, 2=r2, 3=r3, 4=b1, 5=b2, 6=b3'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `scout`
--

CREATE TABLE `scout` (
  `internalid` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `team` int(11) NOT NULL,
  `permission` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL COMMENT 'team number',
  `name` varchar(255) NOT NULL,
  `pic_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `2020_Match`
--
ALTER TABLE `2020_Match`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `2020_Pit`
--
ALTER TABLE `2020_Pit`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `2020_Shots`
--
ALTER TABLE `2020_Shots`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `2020_Submission`
--
ALTER TABLE `2020_Submission`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `2020_TBA`
--
ALTER TABLE `2020_TBA`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_teams`
--
ALTER TABLE `event_teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `match_robot`
--
ALTER TABLE `match_robot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scout`
--
ALTER TABLE `scout`
  ADD PRIMARY KEY (`internalid`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `2020_Match`
--
ALTER TABLE `2020_Match`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto Inc ID Number', AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `2020_Pit`
--
ALTER TABLE `2020_Pit`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto Inc ID Number';
--
-- AUTO_INCREMENT for table `2020_Shots`
--
ALTER TABLE `2020_Shots`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto Inc ID Number', AUTO_INCREMENT=187;
--
-- AUTO_INCREMENT for table `2020_Submission`
--
ALTER TABLE `2020_Submission`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `2020_TBA`
--
ALTER TABLE `2020_TBA`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;
--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `event_teams`
--
ALTER TABLE `event_teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=495;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=587;
--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;
--
-- AUTO_INCREMENT for table `match_robot`
--
ALTER TABLE `match_robot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=637;
--
-- AUTO_INCREMENT for table `scout`
--
ALTER TABLE `scout`
  MODIFY `internalid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;