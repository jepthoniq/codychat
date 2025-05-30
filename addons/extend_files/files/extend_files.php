<?php if ($data['user_language'] != 'Arabic') { ?>
	<script>
		$(document).ready(function() {
			boomAddCss('addons/extend_files/files/main.ex.css');
		});
	</script>
<?php } ?>
<?php if ($data['user_language'] == 'Arabic') { ?>
	<script>
		$(document).ready(function() {
			boomAddCss('addons/extend_files/files/rtl.ex.css');
		});
	</script>
<?php } ?>
<?php if (boomAllow($addons['addons_access'])) { ?>
	<script data-cfasync="false">
		$(document).ready(function() {
			<?php if ($addons['custom1'] > 0) { ?>
				$(document).on('click', '#open_private_pro', function() {
					var id = $(this).attr('data');
					if (user_rank >= 9) {
						getClosedProfile(id);
					}
				});
				getProfile = function(profile) {
					$.post('addons/extend_files/system/profile.php', {
						get_profile: profile,
						cp: curPage,
						token: utk,
					}, function(response) {
						if (response == 1) {
							return false;
						}
						if (response == 2) {
							callSaved(system.noUser, 3);
						} else {
							showEmptyModal(response, 580);
						}
					});
				}
				getClosedProfile = function(profile) {
					$.post('addons/extend_files/system/private_profile.php', {
						get_profile: profile,
						cp: curPage,
						token: utk,
					}, function(response) {
						if (response == 1) {
							return false;
						}
						if (response == 2) {
							callSaved(system.noUser, 3);
						} else {
							showEmptyModal(response, 580);
						}
					});
				}
			<?php } ?>
			<?php if ($addons['custom2'] > 0) { ?>
				processChatPost = function(message) {
					$.post('addons/extend_files/system/chat_process.php', {
						content: message,
						snum: snum,
						token: utk,
					}, function(response) {
						if (response == '') {
							;
						} else if (response == 100) {
							checkRm(2);
						} else {
							$('#name').val('');
							$("#show_chat ul").append(response);
							scrollIt(0);
						}
						waitReply = 0;
					});
				}
			<?php } ?>
			<?php if ($addons['custom3'] > 0) { ?>
				editProfile = function() {
					$.post('addons/extend_files/system/edit_profile.php', {
						token: utk,
					}, function(response) {
						showEmptyModal(response, 580);
					});
				}
			<?php } ?>
			<?php if ($addons['custom4'] > 0) { ?>
				userReload = function(type) {
					if ($('#container_user:visible').length || type == 1 || firstPanel == 'userlist') {
						if (type == 1) {
							panelIt(0);
						}
						$.post('addons/extend_files/system/user_list.php', {
							token: utk,
						}, function(response) {
							chatRightIt(response);
							firstPanel = '';
						});
					}
				}
			<?php } ?>
			dropUser = function(elem, uid, uname, urank, ubot, uflag, cover, age, gender) {
				var avDrop = renderAvMenu(elem, uid, uname, urank, ubot, uflag, cover, age, gender);
				$('#avcontent').html(avDrop);

				if ($('#av_menu').css('left') != '-5000px' && elem == avCurrent) {
					resetAvMenu();
				} else {
					avCurrent = elem;
					var zHeight = $(window).height();
					var zWidth = $(window).width();
					var offset = $(elem).offset();
					var emoWidth = $(elem).width();
					var emoHeight = $(elem).outerHeight();
					var avMenu = $('#avcontent').outerHeight();
					var avWidth = $('#av_menu').width();
					var footHeight = $('#my_menu').outerHeight();
					var inputHeight = $('#top_chat_container').outerHeight();
					var avSafe = avMenu + footHeight;
					var avLeft = offset.left + 10;
					var leftSafe = zWidth - avWidth;
					if (offset.top > zHeight - avSafe) {
						var avTop = zHeight - avSafe;
					} else {
						var avTop = offset.top + emoHeight - 10;
					}
					if (leftSafe > emoWidth) {
						avLeft = offset.left - avWidth + 10;
					}
					$('#av_menu').css({
						'left': avLeft,
						'top': avTop,
						'height': avMenu,
						'z-index': 202,
					});
				}
			}
			loadProSocial = function(id) {
				var proCheck = $('#pro_social').html();
				if ($('#pro_social:visible').length) {
					showMenu('pro_social');
				} else {
					if (proCheck != '') {
						showMenu('pro_social');
					} else {
						$.ajax({
							url: "addons/extend_files/system/pro_social.php",
							type: "post",
							cache: false,
							dataType: 'json',
							data: {
								page: curPage,
								target: id,
								token: utk
							},
							success: function(response) {
								if (response.code == 1) {
									if (response.data == '') {
										$('#prosocial').remove();
									} else {
										$('#pro_social').html(response.data);
										showMenu('pro_social');
									}
								} else {
									hideModal();
									callSaved(system.error, 3);
								}
							},
							error: function() {
								hideModal();
								callSaved(system.error, 3);
							}
						});
					}
				}
			}
			<?php if ($addons['custom5'] > 0) { ?>
				chatReload = function() {
					var cPosted = Date.now();
					var priv = $('#get_private').attr('value');
					logsControl();
					$.ajax({
						url: "addons/extend_files/system/chat_log.php",
						type: "post",
						cache: false,
						timeout: speed,
						dataType: 'json',
						data: {
							fload: fload,
							caction: cAction,
							last: lastPost,
							snum: snum,
							preload: privReload,
							priv: priv,
							lastp: lastPriv,
							pcount: pCount,
							room: user_room,
							notify: globNotify,
							token: utk
						},
						success: function(response) {
							if ('check' in response) {
								if (response.check == 99) {
									location.reload();
									return false;
								} else if (response.check == 199) {
									return false;
								}
							} else {
								var mLogs = response.mlogs;
								var mLast = response.mlast;
								var cact = response.cact;
								var pLogs = response.plogs;
								var pLast = response.plast;
								var getPcount = response.pcount;
								speed = response.spd;
								inOut = response.acd;

								if (response.act != userAction) {
									location.reload();
								} else if (response.ses != sesid) {
									overWrite();
								} else {
									if (mLogs.indexOf("system__clear") >= 1) {
										$("#show_chat ul").html(mLogs);
										if (fload == 1) {
											clearPlay();
										}
										fload = 1;
									} else {
										$("#show_chat ul").append(mLogs);
										if (fload == 1) {
											if (mLogs.indexOf("my_notice") >= 1) {
												usernamePlay();
											}
											if (mLogs.indexOf("system__join") >= 1) {
												joinPlay();
											}
											if (mLogs.indexOf("system__leave") >= 1) {
												leavePlay();
											}
											if (mLogs.indexOf("system__action") >= 1) {
												actionPlay();
											}
											if (mLogs.indexOf("public__message") >= 1) {
												messagePlay();
												tabNotify();
											}
										}
										scrollIt(fload);
										fload = 1;
									}
									cAction = cact;
									lastPost = mLast;
									beautyLogs();
									if ('del' in response) {
										var getDel = response.del;
										for (var i = 0; i < getDel.length; i++) {
											$("#log" + getDel[i]).remove();
										}
									}
									if (response.curp == $('#get_private').attr('value')) {
										if (privReload == 1) {
											if (pLogs == '') {
												$('#private_content ul').html('');
											} else {
												$('#private_content ul').html(pLogs);
											}
											scrollPriv(privReload);
											lastPriv = pLast;
											privReload = 0;
											morePriv = 1;
										} else {
											if (pLogs == '' || lastPriv == pLast) {
												scrollPriv(privReload);
											} else {
												if (response.curp == priv) {
													$("#private_content ul").append(pLogs);
													privDown($(pLogs).find('.target_private').length);
												}
												scrollPriv(privReload);
											}
											if (getPcount !== pCount) {
												privatePlay();
												pCount = getPcount;
												tabNotify();
											} else {
												pCount = getPcount;
											}
											lastPriv = pLast;
										}
									}
									if ('top' in response) {
										var newTopic = response.top;
										if (newTopic != '' && newTopic != actualTopic) {
											$("#show_chat ul").append(newTopic);
											actualTopic = newTopic;
											scrollIt(fload);
										}
									}
									if (response.pico != 0) {
										$("#notify_private").text(response.pico);
										$('#notify_private').show();
									} else {
										$('#notify_private').hide();
									}
									if ('use' in response) {
										var friendsCount = response.friends;
										var newsCount = response.news;
										var noticeCount = response.notify;
										var reportCount = response.report;
										var newNotify = response.nnotif;
										if (newsCount > 0) {
											$('#news_notify').text(newsCount).show();
											if (!$('#chat_left:visible').length) {
												$('#bottom_news_notify').text(newsCount).show();
											}
											if (notifyLoad > 0) {
												if (newsCount > curNews) {
													newsPlay();
												}
											}
										} else {
											$('#news_notify').hide();
											$('#bottom_news_notify').hide();
										}
										if (reportCount > 0) {
											$('#report_notify').text(reportCount).show();
										} else {
											$('#report_notify').hide();
										}
										if (friendsCount > 0) {
											$("#notify_friends").text(friendsCount).show();
										} else {
											$("#notify_friends").hide();
										}
										if (noticeCount > 0) {
											$("#notify_notify").text(noticeCount).show();
										} else {
											$("#notify_notify").hide();
										}
										if (notifyLoad > 0) {
											if (noticeCount > curNotify || friendsCount > curFriends || reportCount > curReport) {
												notifyPlay();
											}
										}
										curNotify = noticeCount;
										curFriends = friendsCount;
										curReport = reportCount;
										curNews = newsCount;
										globNotify = newNotify;
										notifyLoad = 1;
									}
									if ('rset' in response) {
										grantRoom();
									} else {
										ungrantRoom();
									}
									if ('role' in response) {
										roomRank = response.role;
									} else {
										roomRank = 0;
									}
									if ('rm' in response) {
										checkRm(response.rm);
									} else {
										checkRm(0);
									}
									innactiveControl(cPosted);
									systemLoaded = 1;
								}
							}
						},
						error: function() {
							return false;
						}
					});
				}
			<?php } ?>
			getActions = function(id) {
				$.post('addons/extend_files/system/action_main.php', {
					id: id,
					cp: curPage,
					token: utk,
				}, function(response) {
					if (response == 0) {
						callSaved(system.cannotUser, 3);
					} else if (response == 1) {} else {
						overEmptyModal(response, 400);
					}
				});
			}
			getNews = function() {
				closeLeft();
				panelIt(400, 1);
				$.post('addons/extend_files/system/panel_news.php', {
					token: utk,
				}, function(response) {
					chatRightIt(response);
					$('#news_notify, #bottom_news_notify').hide();
				});
			}
		});
		savePrivateProfile = function() {
			$.post('addons/extend_files/system/MA_action.php', {
				set_private_profile: $('#set_private_profile').val(),
				token: utk,
			}, function(response) {
				if (response == 1) {
					callSaved(system.saved, 1);
				} else {
					callSaved(system.error, 3);
				}
			});
		}
	</script>
<?php } ?>