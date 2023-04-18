@if($paginator->hasPages())
<nav class="mb-2 property-pagination">
	<ul class="pagination">
		{{-- Previous Page Link --}}
		@if ($paginator->onFirstPage())
		<li class="disabled page-item aero-pagi" aria-disabled="true" aria-label="&laquo; Previous">
			<a href="javascript:;" class="iconnextpre leftic"><i class="ri-arrow-left-line"></i></a>
		</li>
		@else
		<li class="page-item aero-pagi">
			<a href="javascript:;" class="iconnextpre leftic"><i class="ri-arrow-left-line" onclick="paginate('{{$paginator->previousPageUrl()}}',this,'')"></i></a>
		</li>
		@endif
		{{-- Pagination Elements --}}
		@foreach ($elements as $element)
		{{-- "Three Dots" Separator --}}
		@if (is_string($element))
		<li class="page-item disabled" aria-disabled="true"><span>{{ $element }}</span></li>
		@endif

		{{-- Array Of Links --}}
		@if (is_array($element))
		@foreach ($element as $page => $url)
		@if ($page == $paginator->currentPage())
		<li class="page-item active" aria-current="page"><a class="page-link" href="javascript:;">{{ $page }}</a></li>
		@else
		<li class="page-item"><a href="javascript:;" class="page-link" id="pagenumber" data-pagenumber="{{ $page }}" onclick="paginate('{{$url}}',{{$page}},'')">{{ $page }}</a></li>
		@endif
		@endforeach
		@endif
		@endforeach
		{{-- Next Page Link --}}
		@if ($paginator->hasMorePages())
		<li class="page-item aero-pagi">
			<a href="javascript:;" class="iconnextpre leftic"><i class="ri-arrow-right-line" onclick="paginate('{{$paginator->nextPageUrl()}}',{{$page}},'')"></i></a>
		</li>
		@else
		<li class="page-item disabled aero-pagi" aria-disabled="true" aria-label="@lang('pagination.next')">
			<a href="javascript:;" class="iconnextpre leftic"><i class="ri-arrow-right-line"></i></a>
		</li>
		@endif
	</ul>
</nav>
@endif


@section('uniquePageScript')
<script type="text/javascript">
	$(document).ready(function() {
		paginate();
		if (document.getElementById('propDashboard')) {
			setInterval(() => {
				serach('YES');
			}, 1 * 60 * 1000);
		}
	})

	function paginate(url = '', data, news_type = '', is_loader_stop = '') {

		$("#more_pagination a").removeClass('paginate_active');
		$(data).addClass('paginate_active');
		var nw_type = '';
		var property_type = '';
		var from = '';
		var to = '';

		if ($("select[name='property_type'] option:selected").val()) {
			property_type = $("select[name='property_type'] option:selected").val();
		}


		if ($("input[name='from']").val()) {
			from = $("input[name='from']").val();
		}
		if ($("input[name='to']").val()) {
			to = $("input[name='to']").val();
		}

		if ($("#newsupdate_list").val() != '') {
			nw_type = $("#newsupdate_list").val();
		}
		if (news_type != '') {
			nw_type = news_type;
		}

		var custom_page_number = 1;
		if (parseInt(data)) {
			custom_page_number = parseInt(data);
		}

		if (url == '') {
			var url = "{{Request::getRequestUri()}}";
		}

		if (news_type != undefined && nw_type != undefined) {
			var url = REQUEST_URL + '?type=' + nw_type + '&page=' + custom_page_number;
		}

		var _changeUrl = url;

		if (from != '') {
			_changeUrl = _changeUrl + "&from=" + from;
		}
		if (to != '') {
			_changeUrl = _changeUrl + "&to=" + to;
		}
		if (property_type != '') {
			_changeUrl = _changeUrl + "&property_type=" + property_type;
		}

		if (data != 'nourlchange') {
			window.history.pushState("object or string", "Filter", _changeUrl);
		}
		$.ajax({
			type: "get",
			url: _changeUrl,
			data: {},
			datatype: "html",
			beforeSend: function() {
				if (is_loader_stop != 'YES') {
					$('.ajaxloader').show();
				}
			}
		}).done(function(data) {
			$('.ajaxloader').hide();
			if (data.length == 0) {
				$('.ajaxloader').hide();
				return false;
			}
			if (data['show_msg']) {
				Lobibox.notify(data.type, {
					rounded: false,
					delay: 5000,
					delayIndicator: true,
					position: "top right",
					msg: data.message
				});
			}

			if (data['reloadPage']) {
				location.reload();
			}
			if (data['refresh']) {
				var url = "{{Request::getRequestUri()}}";
				var _changeUrl = url;
				paginate(_changeUrl, 'nourlchange');
			} else {
				if (data['apppendid']) {
					$("#" + data['apppendid']).empty().append(JSON.parse(data['body']));
					$(".myratingview").starRating({
						totalStars: 5,
						starSize: 20,
						activeColor: '#FF6E41',
						useGradient: false,
						readOnly: true
					});
				} else {
					window.history.pushState("object or string", "Filter", _changeUrl);
					if (data['body']) {
						$("#result").empty().append(JSON.parse(data['body']));
					}
				}
			}

		}).fail(function(jqXHR, ajaxOptions, thrownError) {
			$('.ajaxloader').hide();
		});
	}

	function serach(is_loader_stop = '') {
		var order_by = '';
		var from = '';
		var to = '';
		var name = '';
		var email = '';
		var proid = '';
		var sort_by = '';
		var property_type = '';
		var type = '';
		if ($("select[name='order_by'] option:selected").val()) {
			order_by = $("select[name='order_by'] option:selected").val();
		}
		if ($("select[name='sort_by'] option:selected").val()) {
			sort_by = $("select[name='sort_by'] option:selected").val();
		}
		if ($("select[name='property_type'] option:selected").val()) {
			property_type = $("select[name='property_type'] option:selected").val();
		}
		if ($("input[name='from']").val()) {
			from = $("input[name='from']").val();
		}
		if ($("input[name='to']").val()) {
			to = $("input[name='to']").val();
		}
		if ($("input[name='name']").val()) {
			name = $("input[name='name']").val();
		}
		if ($("input[name='email']").val()) {
			email = $("input[name='email']").val();
		}
		if ($("input[name='proid']").val()) {
			proid = $("input[name='proid']").val();
		}
		if ($("input[name='search_type']").val()) {
			type = $("input[name='search_type']").val();
		}
		var url = REQUEST_URL;
		var _URL_ = url;
		var customURL = "?search=" + name;
		if (order_by != '') {
			customURL = customURL + "&status=" + order_by;
		}
		if (from != '') {
			customURL = customURL + "&from=" + from;
		}
		if (to != '') {
			customURL = customURL + "&to=" + to;
		}
		if (email != '') {
			customURL = customURL + "&email=" + email;
		}
		if (proid != '') {
			customURL = customURL + "&proid=" + proid;
		}
		if (sort_by != '') {
			customURL = customURL + "&sortby=" + sort_by;
		}
		if (property_type != '') {
			customURL = customURL + "&property_type=" + property_type;
		}
		if (type != '') {
			customURL = customURL + "&type=" + type;
		}
		var _changeUrl = url + customURL;
		window.history.pushState("object or string", "Filter", _changeUrl);
		$.ajax({
			type: "get",
			url: _changeUrl,
			data: {},
			datatype: "html",
			beforeSend: function() {
				if (is_loader_stop != 'YES') {
					$('.ajaxloader').show();
				}
			}
		}).done(function(data) {
			$('.ajaxloader').hide();
			if (data.length == 0) {
				$('.ajaxloader').hide();
				return false;
			}
			if (data['totalEarnings']) {
				$("#totalEarnings").html(data['totalEarnings']);
				$("#totalBookings").empty().html(data['totalBookings']);
			}
			if (data['totalBookings']) {
				$("#totalBookings").empty().html(data['totalBookings']);
			}
			if (data['body']) {
				$("#result").empty().append(JSON.parse(data['body']));
			}
			//jQuery('#data_filter').dataTable({"paging": false,"bInfo":false, "searching": false});
		}).fail(function(jqXHR, ajaxOptions, thrownError) {
			$('.ajaxloader').hide();
		});
	}

	function AjaxActionTableDrow(obj) {
		var _action = obj.getAttribute('data-action'),
			title = obj.getAttribute('data-title'),
			refresh = obj.getAttribute('data-refresh'),
			reload = obj.getAttribute('data-reload');

		var _type = 'GET';
		if (title == 'Delete') {
			_type = 'DELETE';
		}
		Lobibox.confirm({
			title: title + ' Confirmation',
			msg: 'Are you sure you, want to ' + title + '?',
			callback: function($this, type, ev) {
				if (type === 'yes') {
					$.ajax({
						type: _type,
						url: _action,
						beforeSend: function() {
							$('.ajaxloader').show();
						},
						success: function(data) {
							$('.ajaxloader').hide();
							serach();
							Lobibox.notify(data.type, {
								rounded: false,
								delay: 4000,
								delayIndicator: true,
								position: "top right",
								msg: data.message
							});

							if (reload == 'yes') {
								location.reload();
							}
						},
						error: function(data) {
							console.log('Error:', data);
						}
					});
				} else {
					return false;
				}
			}
		});
	}

	function confirmationBoxAjax(id, title, href) {
		Lobibox.confirm({
			title: title + ' Confirmation',
			msg: 'Are you sure you, want to ' + title + '?',
			callback: function($this, type, ev) {
				if (type === 'yes') {
					paginate(href, 'nourlchange');
					window.location.href = href;
				} else {
					return false;
				}
			}
		});
	}
</script>
@endsection