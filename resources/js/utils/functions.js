import moment from "moment";
import constant from '@/utils/constants';

function formatNumber(number) {
    if (!number) return 0;
    number = number.toString().replace(/,/g, '');
    return number.replace(/(.)(?=(\d{3})+$)/g,'$1,');
}
function formatDate(date) {
    if ( !date ) return '';
    return moment(date).format('DD/MM/YYYY')
}

function times(type = null, price_type = null) {
    var x = 60; //minutes interval
    var times = [];
    var tx_object = getTx(type);
    var tx = tx_object.key;
    var tt = tx*60; // start time
    var time = 60;

    if(price_type == constant.FOUR_HOUR_TYPE){
        x = 240;
    }

    for (var i=0;tt<tx_object.value*time; i++) {
        times.push({label: t(tt)+' - '+t(tt+x), start_time: n(tt), end_time: n(tt+x)})
        tt = tt + x;
    }

    function t(tt) {
        var hh = Math.floor(tt/60);
        var mm = (tt%60);
        return ("0" + hh).slice(-2) + 'h' + ("0" + mm).slice(-2)
    }
    function n(tt) {
        var hh = Math.floor(tt/60);
        var mm = (tt%60);
        return ("0" + hh).slice(-2) + ':' + ("0" + mm).slice(-2)+':00';
    }
    function getTx(type) {
        switch(type) {
            case 1:
                return {key: 7.5,  value: 11};
            case 2:
                return {key: 12.5 , value: 16};
            case 3:
                return {key: 17 , value: 21};
            default:
                return {key: 7.5 , value: 11}
        }
    }

    return times;
}

function objectForTypePrice() {
    return [
        {
            id : constant.CENTRAL_TYPE,
            price_types: [
                {
                    id : constant.TRONG_HINH_TYPE,
                    text : "Tập trong hình"
                },
                {
                    id : constant.DUONG_TRUONG_TYPE,
                    text : "Tập đường trường"
                }
            ],
            time_types: [
                {
                    id : constant.TRONG_GIO_HANH_CHINH_TYPE,
                    text : "Trong giờ hành chính",

                },
                {
                    id : constant.NGOAI_GIO_HANH_CHINH_TYPE,
                    text : "Ngoài giờ hành chính",

                }
            ]
        },
        {
            id : constant.OUTSIDE_CENTRAL_TYPE,
            price_types: [
                {
                    id : constant.FOUR_HOUR_TYPE,
                    text : "1 buổi (4 giờ)",
                },
                {
                    id: constant.ONE_HOUR_TYPE,
                    text: "1 giờ",
                }
            ],
            time_types: [
                {
                    id : constant.TRONG_GIO_HANH_CHINH_TYPE,
                    text : "Trong giờ hành chính",

                },
                {
                    id : constant.BUOI_SANG_CHIEU_TYPE,
                    text : "Ngoài giờ hành chính(buổi sáng)",
                },
                {
                    id : constant.BUOI_TOI_TYPE,
                    text : "Ngoài giờ hành chính(buổi tối)",
                }
            ]
        },
        {
            id : constant.PERSONAL_TYPE,
            price_types: [
                {
                    id : constant.FOUR_HOUR_TYPE,
                    text : "1 buổi (4 giờ)",
                },
                {
                    id: constant.ONE_HOUR_TYPE,
                    text: "1 giờ",
                }
            ],
            time_types: [
                {
                    id : constant.TRONG_GIO_HANH_CHINH_TYPE,
                    text : "Trong giờ hành chính",

                },
                {
                    id : constant.BUOI_SANG_CHIEU_TYPE,
                    text : "Ngoài giờ hành chính(buổi sáng/chiều)",
                },
                {
                    id : constant.BUOI_TOI_TYPE,
                    text : "Ngoài giờ hành chính(buổi tối)",
                }
            ]
        },
    ]
}
function download(res, name='file') {
    console.log(3213)
    const url = window.URL.createObjectURL(new Blob([res.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `${name}.xlsx`)
    document.body.appendChild(link)
    link.click()
}



export default {formatNumber, formatDate, times, objectForTypePrice, download};
