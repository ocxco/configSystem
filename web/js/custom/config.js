var bootbox = window.bootbox
// 初始化datapicker
jQuery(function ($) {
    $.fn.datepicker.dates['zh-CN'] = {
        days: ["日", "一", "二", "三", "四", "五", "六"],
        daysShort: ["日", "一", "二", "三", "四", "五", "六"],
        daysMin: ["日", "一", "二", "三", "四", "五", "六"],
        months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        monthsShort: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
        today: "今天",
        clear: "清空",
        format: "yyyy-mm-dd",
        titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
        weekStart: 0
    };
    $('.input-daterange').datepicker({
        autoclose: true,
        language: 'zh-CN',
        format: 'yyyy-mm-dd',
        clearBtn: true
    });
});

// 表单验证
jQuery(function ($) {
    $('#configForm').validate({
        errorElement: 'div',
        errorClass: 'has-error',
        focusInvalid: false,
        ignore: "",
        rules: {
            namespace_id: {
                required: true
            },
            key: {
                required: true
                // url: true
            },
            name: {
                required: true,
            },
            type: {
                required: true,
            }
        },
        messages: {},
        submitHandler: function (form) {
            $('#nsId').attr('disabled', false);
            var data = $(form).serialize();
            $('#nsId').attr('disabled', 'disabled');
            $.ajax({
                url: '/config/edit',
                data: data,
                method: 'POST',
                success: function (res) {
                    bootbox.alert(res.msg, function () {
                        if (res.status == 1) {
                            location.reload();
                        }
                    });
                },
                fail: function (res) {
                    bootbox.alert(res.msg);
                }
            });
            return false;
        }
    });

});

function typeChange() {
    var v = $('select[name=type]').val()
    if (v == 1) {
        // 数组显示预览按钮
        $('#configPreviewDiv').show();
        $('.configValue').show();
    } else if (v == 4) {
        $('#configPreviewDiv').hide();
        $('.configValue').hide();
    } else {
        $('#configPreviewDiv').hide();
        $('.configValue').show();
    }
}

jQuery(function ($) {
    // 新增配置.
    $('#addConfig').click(function () {
        // 重置表单
        $('#configForm')[0].reset();
        $('#configId').val(0)
        $('#nsId').removeAttr('disabled')
        $('.prefix').html($('.prefix').data('prefix'))
        typeChange();
        $('#configValue').html('')
        $('#configModal').modal('show');
    });

    // 编辑配置.
    $('.configEdit').click(function () {
        var data = $(this).data();
        $.ajax({
            url: '/config/get?id=' + data.id,
            success: function (res) {
                data = res.data
                $('#configId').val(data.id)
                $('#nsId').val(data.namespace_id);
                $('#nsId').attr('disabled', 'disabled');
                $('#configKey').val(data.key)
                $('#configName').val(data.name)
                $('#configType').val(data.type)
                $('#configValue').html(data.value)
                $('.prefix').html(data.prefix)
                typeChange()
                $('#configModal').modal();
            }
        });
    });
    $('#configPreview').click(function () {
        $.ajax({
            url: '/config/preview',
            method: 'post',
            data: $('#configForm').serialize(),
            success: function (res) {
                if (res.status == 1) {
                    $('#previewValue').text(res.data)
                    $('#previewModal').modal('show');
                } else {
                    bootbox.alert(res.msg);
                }
            }
        });
    });
    $('.valuePreview').click(function () {
        $.ajax({
            url: '/config/preview',
            method: 'post',
            data: $(this).data(),
            success: function (res) {
                if (res.status == 1) {
                    $('#previewValue').text(res.data)
                    $('#previewModal').modal('show');
                } else {
                    bootbox.alert(res.msg);
                }
            }
        });
    });
    $('#previewModal').on('show.bs.modal', function () {
        // 预览框显示时，隐藏编辑框
        $('#configModal').css('opacity', 0);
    });
    $('#previewModal').on('hidden.bs.modal', function () {
        // 预览框消失时，显示编辑框
        $('#configModal').css('opacity', 1);
    });
    $('select[name=namespace_id]').change(function () {
        var prefix = $('select[name=namespace_id]').children('option:selected').text()
        $('.prefix').html(prefix);
    });
    $('select[name=type]').change(function () {
        typeChange();
    });
    $('.configPublish').click(function () {
        var data = $(this).data()
        bootbox.confirm("确定发布吗？", function (res) {
            if (!res) {
                return false;
            }
            $.ajax({
                url: '/config/publish',
                method: 'POST',
                data: data,
                success: function (res) {
                    bootbox.alert(res.msg, function () {
                        if (res.status == 1) {
                            location.reload();
                        };
                    });

                }
            });
        });
    });

    $('.publishAll').click(function () {
        var btn = $(this)
        bootbox.confirm("确定发布本页所有配置吗？", function (res) {
            if (!res) {
                return;
            }
            var selected = [];
            $('input[name=choosePublish]').each(function (index, item) {
                selected.push($(item).data('id'));
            });
            var d = btn.data();
            d.ids = selected
            console.log(d);
            $.ajax({
                url: '/config/publish-all',
                method: 'post',
                data: d,
                success: function (res) {
                    bootbox.alert(res.msg, function () {
                        if (res.status == 1) {
                            location.reload();
                        };
                    });
                }
            });
        });
    });
    $('.publishSelected').click(function () {
        var selected = [];
        $('input[name=choosePublish]:checked').each(function (index, item) {
            selected.push($(item).data('id'));
        });
        if (selected.length === 0) {
            return bootbox.alert('至少选中一个配置进行发布');
        }
        var btn = $(this)
        bootbox.confirm("确定发布选中配置吗？", function (res) {
            if (!res) {
                return;
            }
            var d = btn.data();
            d.ids = selected
            $.ajax({
                url: '/config/publish-all',
                method: 'post',
                data: d,
                success: function (res) {
                    bootbox.alert(res.msg, function () {
                        if (res.status == 1) {
                            location.reload();
                        };
                    });
                }
            });
        });
    });
    $("input[name=choosePublish]").change(function () {
        var selected = [];
        $('input[name=choosePublish]:checked').each(function (index, item) {
            selected.push($(item).data('id'));
        });
        if (selected.length > 0) {
            $('.publishSelected').addClass('btn-danger')
                .removeClass('btn-default');
        } else {
            $('.publishSelected')
                .removeClass('btn-danger')
                .addClass('btn-default');
        }
    });
});
