<?php
$sql = "
	
	drop table `ims_fm_photosvote_advs`;
	drop table `ims_fm_photosvote_announce`;
	drop table `ims_fm_photosvote_awarding`;
	drop table `ims_fm_photosvote_awardingtype`;
	drop table `ims_fm_photosvote_banners`;
	drop table `ims_fm_photosvote_bbsreply`;
	drop table `ims_fm_photosvote_data`;
	drop table `ims_fm_photosvote_gift`;
	drop table `ims_fm_photosvote_giftmika`;
	drop table `ims_fm_photosvote_iplist`;
	drop table `ims_fm_photosvote_iplistlog`;
	drop table `ims_fm_photosvote_provevote`;
	drop table `ims_fm_photosvote_provevote_name`;
	drop table `ims_fm_photosvote_provevote_voice`;
	drop table `ims_fm_photosvote_reply`;
	drop table `ims_fm_photosvote_votelog`;
";
pdo_run($sql);