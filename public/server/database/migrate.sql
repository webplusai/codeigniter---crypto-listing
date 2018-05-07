-- 09 Nov 2017
UPDATE `wp_options` SET `option_value` = 'https://www.coinschedule.com/blog' WHERE `wp_options`.`option_id` = 1;
UPDATE `wp_options` SET `option_value` = 'https://www.coinschedule.com/blog' WHERE `wp_options`.`option_id` = 2;

-- 11 Nov 2107
ALTER TABLE `tbl_pages` ADD `PageKeyword` VARCHAR(255) NOT NULL AFTER `PageTitle`, ADD `PageDescription` VARCHAR(255) NOT NULL AFTER `PageKeyword`;
ALTER TABLE `tbl_pages` CHANGE `PageKeyword` `PageKeyword` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `PageDescription` `PageDescription` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE tbl_pages DROP PRIMARY KEY;
ALTER TABLE `tbl_pages` ADD `PageID` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`PageID`);
INSERT INTO `tbl_pages` (`PageID`, `PageName`, `PageTitle`, `PageKeyword`, `PageDescription`, `PageContents`) VALUES (NULL, 'Homepage', 'The best cryptocurrency ICOs (Initial Coin Offering) Crowdsale and Token Sales List', 'ico,icos,ico list,cryptocurrency,crowdsale,token sale,crowdfunding,cryptocoin,bitcoin,altcoin,roadmap', 'List of Cryptocurrency ICOs (Initial Coin Offering) and Token Sales, Milestones, Roadmaps and Events for Bitcoin, Ethereum, Waves, Ripple and other altcoins', '');

-- 16 Nov 2017
ALTER TABLE `tbl_users` ADD `first_pass` TINYINT(2) NOT NULL DEFAULT '0' AFTER `WavesAddress`;

-- 19 Nov 2017
ALTER TABLE `tbl_projects_logs` ADD `ProjTopOfUpcoming` TINYINT(1) NOT NULL DEFAULT '0' AFTER `EventDesc`;
ALTER TABLE `tbl_projects_logs` ADD `ProjHighlighted` TINYINT(4) NOT NULL DEFAULT '0' AFTER `ProjTopOfUpcoming`;
ALTER TABLE `tbl_users` ADD `level` TINYINT(2) NOT NULL DEFAULT '2' AFTER `first_pass`;

-- 21 Nov 2017
ALTER TABLE `tbl_links` ADD INDEX `idx_LinkParentID` (`LinkParentID`);
ALTER TABLE `tbl_links` ADD INDEX `idx_LinkParentType_LinkParentID` (`LinkParentType`, `LinkParentID`);

-- 22 Dec 2017
ALTER TABLE `tbl_submissions` CHANGE `SubType` `SubType` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `tbl_submissions` CHANGE `SubPlatform` `SubPlatform` VARCH INT NOT NULL DEFAULT '0';
ALTER TABLE `tbl_projects` CHANGE `ProjAltSymbol` `ProjAltSymbol` VARCHAR CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `tbl_projects` CHANGE `ProjHasReferal` `ProjHasReferal` INT NOT NULL DEFAULT '0';
ALTER TABLE `tbl_projects` CHANGE `ProjAlgo` `ProjAlgo` VARCHAR(255) CHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `tbl_submissions` CHANGE `SubLink` `SubLink` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `tbl_submissions` CHANGE `SubStart` `SubStart` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00', CHANGE `SubEnd` `SubEnd` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `tbl_submissions` CHANGE `SubLogoLink` `SubLogoLink` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `SubWhitePaper` `SubWhitePaper` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `SubSymbol` `SubSymbol` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `SubSupply` `SubSupply` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `SubTwitter` `SubTwitter` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `SubReddit` `SubReddit` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `SubSlack` `SubSlack` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `SubBitcoinTalk` `SubBitcoinTalk` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `SubProjType` `SubProjType` INT(11) NULL, CHANGE `SubProjCatID` `SubProjCatID` INT(11) NULL;
ALTER TABLE `tbl_submissions` CHANGE `SubCoinName` `SubCoinName` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `tbl_submissions` CHANGE `SubStatusUpdatedOn` `SubStatusUpdatedOn` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `tbl_payments` CHANGE `PayDatetime` `PayDatetime` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `tbl_submissions_team` CHANGE `SubTeamPicture` `SubTeamPicture` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `SubTeamShortBio` `SubTeamShortBio` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `SubTeamLinkedin` `SubTeamLinkedin` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `SubTeamPassport` `SubTeamPassport` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

ALTER TABLE `tbl_projects` CHANGE `ProjICORankTest` `ProjICORankTest`ARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `tbl_projects` CHANGE `ProjTotalSuppNote` `ProjTotalSuppNote` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `tbl_projects` CHANGE `ProjPreMined` `ProjPreMined` DECIMAL(15,1) NOT NULL DEFAULT '0.0';
ALTER TABLE `tbl_projects` CHANGE `ProjPreMinedNote` `ProjPreMinedNote` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `tbl_projects` CHANGE `ProjPageHtmlHeadCode` `ProjPageHtmlHeadCode` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `tbl_events` CHANGE `EventDesc` `EventDesc` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `tbl_events` CHANGE `EventImage` `EventImage` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `EventImageLarge` `EventImageLarge` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

ALTER TABLE `tbl_events` CHANGE `EventTotalRaised` `EventTotalRaised` DECIMAL UNSIGNED NULL, CHANGE `EventTotalRaisedProjID` `EventTotalRaisedProjID` INT(11) NULL, CHANGE `EventTotalRaisedScrapeURL` `EventTotalRaisedScrapeURL` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `EventTotalRaisedScrapeDomQuery` `EventTotalRaisedScrapeDomQuery` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `EventTotalRaisedScrapeDomQueryItem` `EventTotalRaisedScrapeDomQueryItem` INT(11) NULL, CHANGE `EventTotalRaisedScrapeDomQueryItemAttrib` `EventTotalRaisedScrapeDomQueryItemAttrib` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `EventParticipants` `EventParticipants` INT(11) NULL, CHANGE `EventParticipantsScrapeURL` `EventParticipantsScrapeURL` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `EventParticipantsScrapeDomQuery` `EventParticipantsScrapeDomQuery` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `EventParticipantsScrapeDomQueryItem` `EventParticipantsScrapeDomQueryItem` INT(11) UNSIGNED NULL, CHANGE `EventParticipantsScrapeDomQueryItemAttrib` `EventParticipantsScrapeDomQueryItemAttrib` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `EventRate` `EventRate` DECIMAL NULL, CHANGE `EventRatesNote` `EventRatesNote` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `tbl_projects` CHANGE `ProjAltSymbol` `ProjAltSymbol` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

ALTER TABLE `tbl_projects_logs` CHANGE `ProjAltSymbol` `ProjAltSymbol` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `tbl_projects_logs` CHANGE `ProjCatID` `ProjCatID` INT(11) NULL, CHANGE `ProjHasReferal` `ProjHasReferal` INT(11) NULL, CHANGE `ProjAlgo` `ProjAlgo` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `ProjTotalSuppNote` `ProjTotalSuppNote` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `ProjPreMined` `ProjPreMined` DECIMAL(15,1) NOT NULL DEFAULT '0.0', CHANGE `ProjPreMinedNote` `ProjPreMinedNote` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `ProjPageHtmlHeadCode` `ProjPageHtmlHeadCode` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `tbl_projects_logs` CHANGE `ProjAllowsTokens` `ProjAllowsTokens` TINYINT NOT NULL DEFAULT '0';

-- 28 Dec 2017
CREATE TABLE IF NOT EXISTS `tbl_crons` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `response` tinytext,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_crons` ADD PRIMARY KEY (`id`);
ALTER TABLE `tbl_crons` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

-- 02 Jan 2018
ALTER TABLE `tbl_payments` ADD `PayScreenLastViewed` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `PayCreatedDate`;

-- 08 Jan 2018
ALTER TABLE `tbl_projects_logs` CHANGE `ProjID` `ProjID` INT(20) NULL;
ALTER TABLE `tbl_projects_logs` CHANGE `ProjUsers` `ProjUsers` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

-- 11 Jan 2018
ALTER TABLE `tbl_projects` ADD `ProjClicks` INT(11) NOT NULL DEFAULT '0' AFTER `ProjDeleted`;
ALTER TABLE `tbl_projects_logs` ADD `ProjClicks` INT(11) NOT NULL DEFAULT '0' AFTER `ProjHighlighted`;

-- 13 Jan 2018
CREATE TABLE IF NOT EXISTS `tbl_partners` (
  `PartID` int(11) NOT NULL,
  `PartContactName` varchar(255) NOT NULL,
  `PartContactEmail` varchar(255) NOT NULL,
  `PartName` varchar(255) NOT NULL,
  `PartImage` varchar(255) NOT NULL,
  `PartDescription` text NOT NULL,
  `PartLink` varchar(255) NOT NULL,
  `PartServices` varchar(255) NOT NULL,
  `PartClicks` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
ALTER TABLE `tbl_partners`
ADD PRIMARY KEY (`PartID`);
ALTER TABLE `tbl_partners`
MODIFY `PartID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `tbl_events` ADD `EventFeatured` TINYINT(2) NOT NULL DEFAULT '0' AFTER `EventDeleted`;


-- 23 Jan 2018
INSERT INTO `tbl_settings` (`SettingID`, `SettingName`, `SettingValue`) VALUES
  (9, 'Package Plus Listing', '0.29'),
  (10, 'Package Silver Listing', '0.5'),
  (11, 'Package Gold Listing', '2'),
  (12, 'Package Top of Upcoming List', '0.6'),
  (13, 'Package Platinum Listing', '5'),
  (14, 'Package Tweet Post + Telegram', '0.05');


-- 24 Jan 2018
ALTER TABLE `tbl_votes` CHANGE `VoteUpDateTime` `VoteUpDateTime` DATETIME NULL, CHANGE `VoteDownDateTime` `VoteDownDateTime` DATETIME NULL, CHANGE `VoteRemovedDateTime` `VoteRemovedDateTime` DATETIME NULL;
ALTER TABLE `tbl_favourties` CHANGE `FavDateTime` `FavDateTime` DATETIME NULL, CHANGE `FavRemovedDateTime` `FavRemovedDateTime` DATETIME NULL;


-- 30 Jan 2018
ALTER TABLE `tbl_projects` ADD `ProjDelisted` TINYINT(2) NOT NULL DEFAULT '0' AFTER `ProjClicks`;

CREATE TABLE `tbl_useractivities` (
  `UActID` int(11) NOT NULL,
  `UActName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tbl_useractivities` (`UActID`, `UActName`) VALUES
  (1, 'Vote Up'),
  (2, 'Vote Down'),
  (3, 'Added to Favourites'),
  (4, 'Removed from Favourites'),
  (5, 'Clicked Link');
ALTER TABLE `tbl_useractivities`
ADD PRIMARY KEY (`UActID`);
ALTER TABLE `tbl_useractivities`
MODIFY `UActID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;


CREATE TABLE `tbl_useractivity` (
  `UAID` int(11) NOT NULL,
  `UAUserID` int(11) NOT NULL,
  `UAActID` int(11) NOT NULL,
  `UADateTime` datetime NOT NULL,
  `UAProjID` int(11) NOT NULL,
  `UAEventID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `tbl_useractivity`
ADD PRIMARY KEY (`UAID`);
ALTER TABLE `tbl_useractivity`
MODIFY `UAID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

-- 31 Jan 2018
alter table tbl_projects convert to character set utf8 collate utf8_unicode_ci;
alter table tbl_events convert to character set utf8 collate utf8_unicode_ci;


-- 01 Feb 2018
ALTER TABLE `tbl_people_projects` ADD `PeopleProjGroupID` INT(11) NOT NULL DEFAULT '1' AFTER `PeopleProjPosition`;

CREATE TABLE `tbl_peoplegroups` (
  `PeopleGroupID` int(11) NOT NULL,
  `PeopleGroupName` varchar(255) NOT NULL,
  `PeopleGroupSort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `tbl_peoplegroups` (`PeopleGroupID`, `PeopleGroupName`, `PeopleGroupSort`) VALUES
  (1, 'Team', 1),
  (2, 'Advisors', 2);
ALTER TABLE `tbl_peoplegroups`
ADD PRIMARY KEY (`PeopleGroupID`);
ALTER TABLE `tbl_peoplegroups`
MODIFY `PeopleGroupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

-- 02 Feb 2018
ALTER TABLE `tbl_projects` ADD `ProjIsConvertBase64` TINYINT(2) NOT NULL DEFAULT '0' AFTER `ProjDelisted`;
UPDATE `tbl_people_projects` SET `PeopleProjGroupID` = 2 WHERE `PeopleProjPosition` LIKE "%advisor%";

-- 09 Feb 2018
UPDATE `tbl_pages` SET `PageDescription` = 'Coinschedule is one of the first and most established ICO Portals in the world, listing the Best ICOs and Events' WHERE `tbl_pages`.`PageID` = 1;
UPDATE `tbl_pages` SET `PageTitle` = 'Coinschedule: ICO List Of The Best Crypto ICOs and Token Sales' WHERE `tbl_pages`.`PageID` = 6;
alter table tbl_people convert to character set utf8 collate utf8_unicode_ci;
alter table tbl_peoplegroups convert to character set utf8 collate utf8_unicode_ci;
alter table tbl_people_events convert to character set utf8 collate utf8_unicode_ci;
alter table tbl_people_projects convert to character set utf8 collate utf8_unicode_ci;

-- 14 Feb 2018
ALTER TABLE `tbl_projects` ADD `ProjHeaderImage` VARCHAR(255) NULL DEFAULT NULL AFTER `ProjCountryID`;
ALTER TABLE `tbl_projects_logs` ADD `ProjHeaderImage` VARCHAR(255) NULL DEFAULT NULL AFTER `ProjCountryID`;

-- 24 Feb 2018
ALTER TABLE `tbl_apikeys` ADD `id` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);

-- 01 Mar 2018
ALTER TABLE `tbl_payments` ADD `PayRequestServer` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0: old, 1: new, 2: requested, 3: failed' AFTER `PayScreenLastViewed`;
ALTER TABLE `tbl_payments` CHANGE `PayAmount` `PayAmount` DECIMAL(20,8) NOT NULL, CHANGE `PayBalance` `PayBalance` DECIMAL(20,8) NOT NULL DEFAULT '0.00';

-- 12 Mar 2018
ALTER TABLE `tbl_favourties`
DROP `FavRemoved`,
DROP `FavRemovedDateTime`;

-- 13 Mar 2018
ALTER TABLE `tbl_events`
ADD COLUMN `EventDatesNotDefined` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `EventHardCap`;

ALTER TABLE `tbl_apikeys`
CHANGE COLUMN `max` `max` INT(11) NULL DEFAULT '60';