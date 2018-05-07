<style>
.dropdown-menu li {
  font-size: 1.1em;
  padding: 2px;
}
.dropdown-menu .icon {
  padding-right: 5px;
}
.dropdown-menu .divider {
    height: 1px;
    padding: 0px;
    background: #f1f3f6;
}
.dropdown .badge {
    padding: 3px 6px;
    margin-bottom: 2px;
}

.usericon {
  background: #777;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  font-size: 25px;
  color: #fff;
  text-align: center;
}

.badge {
    position: absolute;
    top: 6px;
    right: 6px;
}

</style>

		<!-- SmartAdmin Styles : Caution! DO NOT change the order -->
		<link rel="stylesheet" type="text/css" media="screen" href="https://www.coinschedule.com/smartadmin/HTML_Full_Version/css/smartadmin-production-plugins.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="https://www.coinschedule.com/smartadmin/HTML_Full_Version/css/smartadmin-production.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="https://www.coinschedule.com/smartadmin/HTML_Full_Version/css/smartadmin-skins.min.css">
    
    <!-- SmartAdmin RTL Support  -->
		<link rel="stylesheet" type="text/css" media="screen" href="https://www.coinschedule.com/smartadmin/HTML_Full_Version/css/smartadmin-rtl.min.css">
    
    	<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="js/libs/jquery-2.1.1.min.js"><\/script>');
			}
		</script>
    
    <!-- IMPORTANT: APP CONFIG -->
		<script src="https://www.coinschedule.com/smartadmin/HTML_Full_Version/js/app.config.js"></script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
		<script src="https://www.coinschedule.com/smartadmin/HTML_Full_Version/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 

		<!-- BOOTSTRAP JS -->
		<script src="https://www.coinschedule.com/smartadmin/HTML_Full_Version/js/bootstrap/bootstrap.min.js"></script>

		<!-- CUSTOM NOTIFICATION -->
		<script src="https://www.coinschedule.com/smartadmin/HTML_Full_Version/js/notification/SmartNotification.min.js"></script>
    
    <!-- MAIN APP JS FILE -->
		<script src="https://www.coinschedule.com/smartadmin/HTML_Full_Version/js/app.min.js"></script>



				<!-- Note: The activity badge color changes when clicked and resets the number to 0
				Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
				<span id="activity" class="activity-dropdown"> <i class="fa fa-user"></i> <b class="badge"> 21 </b> </span>

				<!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
				<div class="ajax-dropdown">

					<!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
					<div class="btn-group btn-group-justified" data-toggle="buttons">
						<label class="btn btn-default">
							<input type="radio" name="activity" id="ajax/notify/mail.html">
							Msgs (14) </label>
						<label class="btn btn-default">
							<input type="radio" name="activity" id="ajax/notify/notifications.html">
							notify (3) </label>
						<label class="btn btn-default">
							<input type="radio" name="activity" id="ajax/notify/tasks.html">
							Tasks (4) </label>
					</div>

					<!-- notification content -->
					<div class="ajax-notifications custom-scroll">

						<div class="alert alert-transparent">
							<h4>Click a button to show messages here</h4>
							This blank page message helps protect your privacy, or you can show the first message here automatically.
						</div>

						<i class="fa fa-lock fa-4x fa-border"></i>

					</div>
					<!-- end notification content -->

					<!-- footer: refresh area -->
					<span> Last updated on: 12/12/2013 9:43AM
						<button type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Loading..." class="btn btn-xs btn-default pull-right">
							<i class="fa fa-refresh"></i>
						</button> 
					</span>
					<!-- end footer -->

				</div>
				<!-- END AJAX-DROPDOWN -->
















<li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                     <span class="glyphicon glyphicon-envelope" style="font-size: 20px;"></span>
                                    <span class="badge badge-default"> 7 </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="external">
                                        <h3>
                                            <span class="bold">12 pending</span> notifications</h3>
                                        <a href="page_user_profile_1.html">view all</a>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="time">just now</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-success">
                                                            <i class="fa fa-plus"></i>
                                                        </span> New user registered. </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="time">3 mins</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> Server #12 overloaded. </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="time">10 mins</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-warning">
                                                            <i class="fa fa-bell-o"></i>
                                                        </span> Server #2 not responding. </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="time">14 hrs</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-info">
                                                            <i class="fa fa-bullhorn"></i>
                                                        </span> Application error. </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="time">2 days</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> Database overloaded 68%. </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="time">3 days</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> A user IP blocked. </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="time">4 days</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-warning">
                                                            <i class="fa fa-bell-o"></i>
                                                        </span> Storage Server #4 not responding dfdfdfd. </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="time">5 days</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-info">
                                                            <i class="fa fa-bullhorn"></i>
                                                        </span> System Error. </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="time">9 days</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> Storage server failed. </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

<li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <!-- <img alt="" class="img-circle" src="https://www.coinschedule.com/metronic/assets/layouts/layouttest/img/avatar3_small.jpg" />  -->
                                    <!-- <span class="username username-hide-on-mobile"><? echo $user_info['tx_username']; ?></span> -->
                                    <span class="glyphicon glyphicon-user" style="font-size: 20px;"></span>
                            
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="alerts.php">
                                            <i><img class="icon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAC0UlEQVQ4T33Re0hTURwH8O+9m+U252NbutRySgqGGkjWP0UG1R8i0QNDTc3hCyKshOwB5iIlQYgs88EwWVZaitIfBZl/BGHSAzMNMtSEtrW1zbu73T3MbffGlZTKxw8OHM45v8/5nd8hsE60lREDDi8iLj7gMtc6Rvy/odGATKIFhyPDue0OhkicNrOJ/gBnkYeg3xyLRxoN2L9zVgBtFciKjlWV7D1SlsERwUrabhKwPi/z4WX/e4Hf2pjT6B9cF2gpJeuz1dX5stAo1dycBSbDBOKUIixwYv3Is6723Fts/brAHTXZXl7TUmjRG0VGox6M8ye2RgkRq4rz9ne0tRU2+6vWBJ7kQGAQkdpz9R3FX8bGCJebgZM2QSYlkJYaz3U1t96Nj2bP79fAv4T804PWIsQQIYqGisuNBZPj4/B4XKDtBsikJFKSY/BY16v1/bJfVbfAvCpwMx+7tiRn1BwvKM/+NjUFxkWDpgyQS0kkJcgwNDjSZ5yeulHRidFVgcZc5O88cKxyz76Duw3673A4KTgo/WIFqs1ijH3Sv/749t3tyvuBvhUA//6vfvJa8YVatSIsPNpms4CirHA6jIiQkFDKhXDPk4buzgFtAtj6E70I8MhyD+qOYk94lKq0pKq6yOfxEA6HHVbbD3hcVkhFBOShJCQhEq5bN3iPnrN1XunH8DKgyYLSx5JnT1+vK1ZsilSyLIt5rwu0zQifh8IGYQBiEYfgjSRoymlqb3qqCwLbpHkO82IFZzJxKa+8/GRi2o4UykaDcbrhZtzwOO1gfV4EBbGQiAmEhQqgiBBiZnL2c0/Pm4fNr9CwCJxKhzoQwKGQcFkMKRRFsRxH8uut2tptQ0ND6OsZnuEPEiQCnM9tZhiHSUjghW4UnUs9EAKQA1AACOaT42VIT41EHj+fsKB7llr+ugUAVgA2AP4lgL9R/Gfw2HrBd98NwAOA/Q3vniwgoByFAwAAAABJRU5ErkJggg=="></i> Alerts
                                            <span class="badge badge-danger"> 3 </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="listings.php">
                                            <i><img class="icon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABxklEQVR4Xo2STWsTQRzGf/+ZScEebNWSfAH1QwTFmwgiFuuhtqkXLx69tUTpC1hzUqTiN5D00GZTak6ll34S295CE19Ck76wu383Q8HdEKs/ePY/swwPD8+MqCoA243GjzCKxjWOUdIoIBgRROToyeRkngtU1X+8avW6/otms6mbQVBNG5iMG/Bt/yDRIfsHh/3p9wBfqlXy+Tx3isVnm/X6Ohe4VFCPcxZjDAIISuyN8f+OWi0KhQJ3i8XpYGsrBzx1kLVwbgRnDQ5lRCJUYwBu37rJzu4u/Y5KMzOo6hTwp4ONWk37tNptfV1e0LNOW/moygdVPe3oIBtB0B84BjDGslB+gxCiL1ugCudC7+QMv4zh2o0JUM12QKqDyupbVlfKyNQE3AdCErPvyKfrnCYzjWMAEcvS8gpIiG53AIUIwKGvfhGeZ0MbBsjlLMtLi+As8ugqsjYG1iKPk/XnMVzOXp7AGsO7SgUI0a8ngAIGbfTwhObyBL7E+XnAIg+uAA55OIqsJXo/CiaVIHONQaBZ4kThEEXDr9EYQxYBLMPx57MddI+P95LneS9WhUR/RQSTqNvt7mUMnpdKL4Bx/p+fc7Oz/Ab5HTFRfgdBJAAAAABJRU5ErkJggg=="></i> Listings
                                            
                                        </a>
                                    </li>
                                    <li class="divider"> </li>
                                    <li>
                                        <a href="profile.php">
                                            <i><img class="icon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAATklEQVR42mNkoBAwUmxAQ0PDf4oNqK+vJ0tzY2MjDQwACYIANjFs4igGICtEV0yUC3AZgE+cKC9QzQXohlI/DEgF1DOALN1QQHlmotQAAJJJVwWMGrVXAAAAAElFTkSuQmCC"></i> Profile </a>
                                    </li>
                                    <li>
                                        <a href="widgets.php">
                                            <i><img class="icon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAC2UlEQVR4Xo2SX0xTZxjGn1NpC1KwsZZIERUoxGVl1MI0/ClguxIlizFRF7KQRdRLTYxuN2qQ1hp2tQTHxbIoJOiIgSzChdBCrBc2KrS04ACrCX9aGI0LtrbFItDTz/OdIPgHkz3J7+TJ977fm+fN+RhsIMO1PrJMCO9FDIP+S1UMviDBe/PdNetdvdn6kvqVGItfjqgpvKeiNdqDjUQLZ9qcpKFrlOivWsm5205y+I8BCvX0jK/Rnk+HJFATZ+NaqUQMZYYMGmUalyCOuSE/qLSqdOjUOxFeZBGLz/O9n61gqz+kdzzz4y+XH/bJ12ixe/FqPkihnp7xtUGuh/Z+NEBntpLyhp5hVdY25MpTMDYTwlIwCP+jrpMU6kf/jUApT4UqOw1lRutwhdlG1lbIlEtQq1chFF1Bu/s/xBejXHzLqfHu6538ehBgR3lNy9TCJvyoUqBSnYXWh3PrCYLROHqfh2CbDEMkFEKSlIDk9GwXgAVKsrLEdzzaBhkbQNmDChRYSpAUmsBTo7pp9Z9bic7USw7//oT81DlFmu2zxGDqeVFhtJSVmmy5NxrqSPDOfjJzs5iw7u/J28fVxNuiJbMdBuIwFbcyAAo5wA1xZhXk42u5GBlJBA6PHzFBIoq2RqD1noVCI0PEFwHDMEjZuQXT7gjON49qBACGuEfSU12chxzZZngCLF6xYhz4Ng9ajtKRWv5y2BvBFVE/1PZGd8QXRqZGisYTX7n4dxCLhsZm5t+khePLmJ7wIpyZAZlCgUUA+xiA4aCf1JcDKGLGWAIGq1pzOeUXO24IEyXf3K+vrtIZ7zn36SqxBDE8/d34c/dv2L6XTwHwK6TCx61wtdVzlMG6cjgkHCMGs4VIpFIsEREOBlpRs+cfRN4IkMHFppp1hSCSCDE3HuplsLEK8YH6LuRfbh8M3Pr5B+XfAHC6aURnOpZrqvrVUYf/qV0ciXTwKgkcSg68AwTqSZW5KeRXAAAAAElFTkSuQmCC"></i> Widgets
                                            
                                        </a>
                                    </li>
                                
       
                                    <li class="divider"> </li>

                                     <li>
                                        <a href="https://www.coinschedule.com/logout">
                                            <i><img class="icon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACBUlEQVR4XqWTXUgUURTHz529uzvb7moRFPRkL0JR4JsPPm2Z0Uv4Uhbai2yBL2EfKIKYoFE99AERRgwGaQRS9lAE9kkQFGgvKlgIKhKGILvMrLOzu3M/vHfUmW1n6qUDhzMw5/+/v3s4F3HO4X8CHb41fEfU9D8E2kx3++UgsTwcS3FHe1uSMD8JVhAMDY9Kc9fA1yPL6noBCoTCm/GXucoGWrCeCsoIAKiCxAg00Is2bBOIpgbwwtxZV5+tae1Yw2GMDt3QLsz2pJ+VGyiyUECAFOfT8SjLhX3NbQxjjE4eb4ypqvpImJz1EdgVWJWDlWxKsirUeCwVe//ukzQJCZJR14AAcsUH++9XRyOR8+daWxI2Y45YLxFYNougqonQ0aYjsQ9vPw6Jvlfy1yYB/2NoiFfvgnnDgmyJOueXKIc8ZRBWEFRZRaCUck6I7RGUGfy82WUc6L079vnF81PuoMJhtDt1ImFlMnRx6ms+/2vp6vy9axYMdAbOgM8NXuoU9brIeHx/bbL2Yt9E1NTpihAbP6b7F7Xbzv39BN7MclsJe5uaVUYI//3ty7b4IQBYsi9wiGJpjIp91ZZGHtRwxnYYs98zrricgIosUg6pljPJPVHsaqMhBE8ej6T16ckrAJD1CL3AZN0YW349ftpZaZFBq+yJ/CHZ4/KqTvWHueVr/u01bgCTjubTuaUJUwAAAABJRU5ErkJggg=="></i> Log Out </a>
                                    </li>
                                </ul>
                            </li>