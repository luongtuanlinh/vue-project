import moment from 'moment';
export function formatNumber(number) {
    if (!number) return 0;
    number = number.toString().replace(/,/g, '');
    return number.replace(/(.)(?=(\d{3})+$)/g,'$1,');
}
export function formatDate(date) {
    if ( !date ) return '';
    return moment(date).format('DD/MM/YYYY')
}
export function formatDateTime(dateTime) {
    return moment(dateTime).format('DD-MM-YYYY HH:mm:ss')
}

export function subjectStatus(status) {
    switch (parseInt(status)) {
        case 1: return 'active'
        case 0: return 'inactive'
    }
}

export function typeTicket(type) {
    if (type==1) return 'hình';
    return 'đường trường'
}

export function typeExam(type) {
    switch(parseInt(type)) {
        case 1:
            return 'thi chứng chỉ';
        case 2:
            return 'thi sát hạch';
    }
}

export function feeExam(type) {
    switch(parseInt(type)) {
        case 0:
            return 'chưa nộp';
        case 1:
            return 'đã nộp';
    }
}

export function gender(code) {
    switch (parseInt(code)) {
        case 1: return 'Nam';
        case 0: return 'Nữ';
    }
}
