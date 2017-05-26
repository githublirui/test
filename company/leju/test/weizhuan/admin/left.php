        <div class="span3">
                <div class="sidebar-nav">	
				  <?php 
				  if($_SESSION['admindata']['q']=='网站设置' || $_SESSION['admindata']['q']=='最高权限'){
				  ?>				
                  <div class="nav-header" data-toggle="collapse" data-target="#menu1">				  
				  <i class="icon-dashboard"></i>网站设置
				  </div>
                    <ul id="menu1" class="nav nav-list collapse in">
					<li ><a href="set.php">参数设置</a></li>
						<li ><a href="news.php">新闻发布</a></li>
						<li ><a href="newslist.php">新闻管理</a></li>
						<li ><a href="adlist.php">广告设置</a></li>
                    </ul>
				<?php }?>					
				  <?php 
				  if($_SESSION['admindata']['q']=='CPS管理' ||  $_SESSION['admindata']['q']=='最高权限'){
				  ?>	
<!--				  
				  <div class="nav-header" data-toggle="collapse" data-target="#cps">				  
				  <i class="icon-dashboard"></i>CPS管理			  
				  </div>
                    <ul id="cps" class="nav nav-list collapse in">
						<li ><a href="#">开发中</a></li>
						<li ><a href="#">开发中</a></li>
						<li ><a href="#">开发中</a></li>
                    </ul>	
-->					
				<?php }?>					
				  <?php 
				  if($_SESSION['admindata']['q']=='分享文章' ||  $_SESSION['admindata']['q']=='最高权限'){
				  ?>					
				  <div class="nav-header" data-toggle="collapse" data-target="#menu2">				  
				  <i class="icon-dashboard"></i>分享文章			  
				  </div>	
                    <ul id="menu2" class="nav nav-list collapse in">
						<li ><a href="typelist.php">分类管理</a></li>
						<li ><a href="add_article.php">文章发布</a></li>
						<li ><a href="weixin.php">微信导入</a></li>
						<li ><a href="all_article.php">文章管理</a></li>
						<li ><a href="refererlist.php"><font color=red>访问统计</font></a></li>
						<li ><a href="koulist.php"><font color=red>扣量统计</font></a></li>
                    </ul>
				   <?php }?>			
				  <?php 
				  if($_SESSION['admindata']['q']=='会员管理' ||  $_SESSION['admindata']['q']=='最高权限'){
				  ?>					
				  <div class="nav-header" data-toggle="collapse" data-target="#menu3">				  
				  <i class="icon-dashboard"></i>会员管理			  
				  </div>
                    <ul id="menu3" class="nav nav-list collapse in">
						<li ><a href="user.php">会员管理</a></li>
						<!--<li ><a href="tx.php">提现管理</a></li>-->
                    </ul>
				  <?php }?>	
				  <?php 
				  if($_SESSION['admindata']['q']=='财务管理' ||  $_SESSION['admindata']['q']=='最高权限'){
				  ?>
				  <div class="nav-header" data-toggle="collapse" data-target="#menu4">				  
				  <i class="icon-dashboard"></i>财务管理			  
				  </div>
                    <ul id="menu4" class="nav nav-list collapse in">
						<li ><a href="txlist0.php">等待提现</a></li>
						<li ><a href="txlist1.php">已经提现</a></li>
						<li ><a href="txlist2.php">拒绝支付</a></li>
                    </ul>	
				  <?php }?>
            </div>
        </div>