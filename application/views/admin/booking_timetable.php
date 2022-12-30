<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">入屋現勘/遠端視訊預約時間</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    設定開放預約時間
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>日期</label>
                                <select class="form-control" name="booking_date" id="booking_date" required>
                                    <option value="">選擇日期</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>時間</label>
                                <select class="form-control" name="booking_time" id="booking_time" required>
                                    <option value="">選擇時間</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" id="submit">確認送出</button>
                                <button type="button" class="btn btn-danger" onclick="location.reload()">取消</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
<!-- /#page-wrapper -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    $(document).ready(function () {
        let $booking_date = $('#booking_date');
        let $booking_time = $('#booking_time');

        let booking_timetable = [];

        const date = new Date();
        date.setDate(date.getDate() + 1)
        const week = ['日', '一', '二', '三', '四', '五', '六']
        for (let i = 0; i < 30; i++) {
            const weekStr = `星期${week[date.getDay()]}`
            // get YYYY-MM-DD
            const dateISO = date.toISOString().split('T')[0]
            $booking_date.append($('<option>', {'text': `${dateISO} ${weekStr}`, 'value': dateISO}))
            date.setDate(date.getDate() + 1)
        }

        const apiUrl = '/admin/risk';
        axios.get(`${apiUrl}/get_booking_timetable`, {
            params: {
                start_date: $booking_date.find('option:eq(1)').val(),
                end_date: $booking_date.find('option:eq(-1)').val()
            }
        }).then(({data}) => {
            if (data.result !== 'SUCCESS') {
                return;
            }
            $.each(data.data.booking_table, function (key, value) {
                booking_timetable[key] = value;
                let booking_timetable_detail = [];
                $.each(value, function (key2, value2) {
                    $booking_time.append($('<option>', {
                        'text': value2.name,
                        'value': value2.name,
                        'disabled': value2.is_bookable === false,
                        'data-date': key,
                        'hidden': true
                    }));
                });
            });
        })

        $booking_date.on('change', function () {
            let checked_value = $(this).find('option:checked').val();
            $booking_time.find('option').prop('hidden', true);
            $booking_time.find('option:eq(0)').prop('hidden', false).prop('checked', true);
            $booking_time.val('');
            if (!booking_timetable[checked_value]) {
                return;
            }
            $.each(booking_timetable[checked_value], function (key, value) {
                let checked_date = $booking_date.find('option:checked').val();
                $booking_time.find(`option[data-date="${checked_date}"]`).prop('hidden', false);
            });
        });

        $('button#submit').on('click', function () {
            let date = $booking_date.find('option:checked').val();
            let time = $booking_time.find('option:checked').val();

            if (!date) {
                alert('請選擇日期');
                return;
            }
            if (!time) {
                alert('請選擇時間');
                return;
            }
            $('button').prop('disabled', true);
            axios.post(`${apiUrl}/create_booking`, {
                date: date,
                time: time
            }).then(({data}) => {
                if (data.result !== 'SUCCESS') {
                    alert('送出失敗');
                    return;
                }

                alert('送出成功');
                location.reload();
            })
        })
    });
</script>