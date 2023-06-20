<template>
    <div>
        <form id="AddDetails" @submit.prevent="addCalendar" @keydown="clearAllError">
            <div class="content-header ">
                <div class="container-fluid ml-0">
                    <div class="row">
                        <div class="col-md-6">
                            <ol class="breadcrumb ">
                                <li class="breadcrumb-item">
                                    <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> <a
                                            href="/calendar/calendars"> Lịch làm việc</a> </h5>
                                </li>
                                <li class="breadcrumb-item active">
                                    <span v-if="id != null">{{ $t('form.edit') }}</span>
                                    <span v-else>{{ $t('form.create') }}</span>
                                </li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <div class="float-sm-right " style="margin-top:-5px; ">
                                <a href="/calendar/calendars" class="btn btn-default ">Huỷ</a>
                                <button type="submit" :disabled="loading" value="Lưu" class="btn btn-primary">
                                    Lưu</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div v-if="hasAnyError > 0">
                        <div class="alert alert-warning">
                            <button type="button" class="close" @click.prevent="clearAllError()">
                                <i class="ace-icon fa fa-times"></i>
                            </button>
                            <ul>
                                <li v-for="(err, index) in errors" v-bind:key="index">{{ err }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card  card-default">
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="thongtinchung" role="tabpanel"
                                    aria-labelledby="thongtinchung-tab">
                                    <div class="row  ">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left"
                                                    for="calendar_type_id">
                                                    <span>Công Ty</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <select class="form-control form-control-sm" id="company_id"
                                                        v-model="calendar.company_id" name="calendar_type_id"
                                                        v-bind:class="hasError('company_id') ? 'is-invalid' : ''" >

                                                        <option v-for="company in companies" :key="company.id"
                                                            v-bind:value="company.id">
                                                            {{ company.name }}
                                                        </option>
                                                    </select>
                                                    <span v-if="hasError('company_id')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('company_id') }}</strong>
                                        </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span>Tiêu đề</span>
                                                    <small class="text-red"></small>
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-sm" id="title"
                                                        v-model="calendar.title" name="title" placeholder="" 
                                                        v-bind:class="hasError('title') ? 'is-invalid' : ''"/>
                                                        <span v-if="hasError('title')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('title') }}</strong>
                                        </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left"
                                                    for="calendar_type_id">
                                                    <span>Loại</span>
                                                    <small class="text-red">( * )</small>
                                                </label>
                                                <div class="col-md-8">
                                                    <select class="form-control form-control-sm" id="calendar_type_id"
                                                        v-model="calendar.calendar_type_id" name="calendar_type_id"
                                                        v-bind:class="hasError('calendar_type_id') ? 'is-invalid' : ''">
                                                        <option v-for="calendartype in calendar_types"
                                                            :key="calendartype.id" v-bind:value="calendartype.id">
                                                            {{ calendartype.name }}
                                                        </option>
                                                    </select>
                                                    <span v-if="hasError('calendar_type_id')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('calendar_type_id') }}</strong>
                                        </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label type="year" class="col-form-label-sm col-4 "
                                                    style="text-align:left" for="">
                                                    <span>Năm</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <select class="form-control form-control-sm" id="year"
                                                        v-model="calendar.year" name="year"
                                                        v-bind:class="hasError('year') ? 'is-invalid' : ''">
                                                        <option v-for="CurrentYear in getCurrentYear()" :value="CurrentYear">
                                                        <span v-if="CurrentYear==year">{{CurrentYear}} (*)</span>
                                                        <span v-else>{{CurrentYear}}</span>
                                                    </option>
                                                    </select>
                                                    <span v-if="hasError('year')" class="invalid-feedback" role="alert">
                                            <strong>{{ getError('year') }}</strong>
                                        </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span>Mô tả</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control  textarea" id="description"
                                                        v-model="calendar.description" name="description"
                                                        placeholder=" "></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <b-card no-body>
                                        <b-tabs card small
                                            active-nav-item-class="font-weight-bold text-uppercase text-black">
                                            <b-tab title="Bảng lịch làm việc">
                                                <div class="form-group ">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <div @click.prevent="showTop = !showTop"
                                                                class="btn btn-outline-danger ">{{ showTop? 'Đóng': 'Mở' }} Form xóa
                                                            </div>
                                                            
                                                            <b-alert v-model="showTop"
                                                            @drop="onDrop($event, 1)" @dragover.prevent @dragenter.prevent
                                                                class="position-fixed fixed-top m-0 rounded-0"
                                                                style="text-align: center;width:75%;z-index: 2000;border-width:2px;border-color: brown;"
                                                                variant="light">
                                                                <!-- <div class="form-group "> -->
                                                                    <div class="row" @drop="onDrop($event, 1)" @dragover.prevent @dragenter.prevent>
                                                                        <div class="col-md-11">
                                                                            <div class="row">
                                                                                <div v-for="catl_del in calendar.calendar_details_del"
                                                                                    class="col-md-2">
                                                                                    <div style="border-radius: 10px;">
                                                                                        <div style="color:white;font-size: 10px;border-radius: 10px;"
                                                                                            v-for="holiday_del in calendar_holidays">
                                                                                            <div class="mb-1"
                                                                                                :style="'background-color:' + holiday_del.color + ';border-radius:5px;font-size: 10px;'"
                                                                                                v-if="holiday_del.id == catl_del.calendar_holiday_id">
                                                                                              
                                                                                                <span>{{ catl_del.day }}/{{ catl_del.month }} {{
                                                                                                    holiday_del.name
                                                                                                }}</span>
                                                                                              
                                                                                                <i class="fas fa-ban" style="size: 30%;right: 2px; opacity: 0.5;
                                                                                                text-align: right;color: red;" @click="canceldeleteholiday(catl_del.day,catl_del.month)"></i>
                                                                                          
                                                                                        </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1" style="text-align: right;">
                                                                          
                                                                            <button class="btn btn-outline-primary" type="button"
                                                                                @click.prevent="CancelAllDelete()"><i class="fad fa-trash-undo"></i></button>
                                                                        </div>
                                                                    </div>
                                                                <!-- </div> -->
                                                            </b-alert>
                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="checkbox" v-model="check.checkholiday" /> Thêm nhanh
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-6" v-if="check.checkholiday == false">
                                                            <label>Loại ngày nghỉ</label>
                                                            <select class="form-control form-control-sm"
                                                                id="calendar_type_id"
                                                                v-model="calendardetails.calendar_holiday_id"
                                                                name="calendar_holiday_id"  @change="change_color()"  :style="{ color: colors}">
                                                                <option v-for="holidays in calendar_holidays"
                                                                    :key="holidays.id" v-bind:value="holidays.id" :style="{ color: holidays.color }">
                                                                    {{ holidays.name }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-3" v-for="month in 12" :key="year + '-' + month">
                                                        <div class='calendar' @click.ctrl.z="CancelAllDelete()">
                                                            <div class="calendar-header">
                                                                <i style="font-weight: 700;text-align:center; color:black; font-size:17px; margin-bottom: 5px;">
                                                                    Tháng {{ month }}</i>
                                                            </div>
                                                            <div class="calendar-wrapper">
                                                                <div class='week-days-wrapper'>
                                                                    <div :class="getClassWeekDayHeader(index)"
                                                                        v-for='(weekday, index) in week_day_labels'
                                                                        :key="year + '-' + month + '-' + weekday">
                                                                        <span> {{ weekday }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class='week-row'
                                                                    v-for='(current_week, week_index) in getWeeksInMonth(calendar.year, month)'
                                                                    :key="year + '-' + month + '-' + week_index">
                                                                    <div :class='getClassDayWrapper(day)'
                                                                        v-for='(day, day_index) in current_week'
                                                                        :key="year + '-' + month + '-' + week_index + '-' + day_index"
                                                                        v-b-tooltip.html.v-danger
                                                                        @click.prevent="saveDay(day.day_of_month, month, calendar.year, year + '-' + month + '-' + week_index + '-' + day_index)"
                                                                        class="dayCantainer">
                                                                        <div v-if="day.day_of_month > 0"
                                                                            :class="getClassDayNumber(day)"> {{
                                                                                day.day_of_month
                                                                            }}
                                                                        </div>
                                                                        <div v-for="catl in calendar.calendar_details">
                                                                            <div style="border-radius: 10px;" class="cal-cell"
                                                                                v-if="catl.day == day.day_of_month && catl.month == month"
                                                                                draggable
                                                                                @dragstart="startDrag($event, day.day_of_month, month)">
                                                                                <div style="color:white;font-size: 10px;border-radius: 2px;"
                                                                                    v-for="holiday in calendar_holidays">
                                                                                    <div class="mb-1"
                                                                                        :style="'background-color:' + holiday.color + ';border-radius:3px;'"
                                                                                        v-if="holiday.id == catl.calendar_holiday_id">
                                                                                        <i class="fas fa-ban" style="size: 30%;right: 2px; opacity: 0.5;
                                                                                                text-align: right;color: red;" @click.stop="deleteholiday(catl.day, catl.month)"></i>
                                                                                        <span>{{ holiday.name }}</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div v-if="day.day_of_week == 1"
                                                                            class="week-of-year">{{
                                                                                day.week_of_year
                                                                            }}
                                                                        </div>
                                                                        <div v-if="day.lunar_day > 0"
                                                                            class="lunar-day-of-month">{{
                                                                                day.lunar_day
                                                                            }}{{
                                                                                day.lunar_day == 1 ? '/' +
                                                                                    day.lunar_month : ''
                                                                            }}</div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-tab>
                                        </b-tabs>
                                    </b-card>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="modal fade" id="addHoliday" tabindex="-1" role="dialog" data-keyboard="false"
            data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" @submit.prevent.enter="addholidays()">
                        <div class="modal-header">
                            <div class="modal-title">
                                <span v-if="!edit_date"><strong> Nhập Ngày</strong></span>
                                <span v-if="edit_date"><strong> Cập nhật Ngày</strong></span>
                            </div>
                            <div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <label>Ngày</label>
                                    <input class="form-control form-control-sm" v-model="calendardetails.day" name="day"
                                        placeholder=" " :disabled="true" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label>Tháng</label>
                                    <input class="form-control form-control-sm" v-model="calendardetails.month"
                                        name="month" placeholder=" " :disabled="true" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label>Năm</label>
                                    <input class="form-control form-control-sm" v-model="calendardetails.year"
                                        name="year" placeholder=" " :disabled="true" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label>Loại ngày nghỉ</label>
                                    <select class="form-control form-control-sm"
                                        v-model="calendardetails.calendar_holiday_id" required>                                    
                                        <option v-for="cal_holidays in calendar_holidays" :value="cal_holidays.id"
                                            :key="cal_holidays.id">
                                            {{ cal_holidays.name }}
                                        </option>
                                        <option value="">
                                           Ngày làm
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer  ">
                            <button @click="closeform()" type="button" class="btn btn-default" data-dismiss="modal">
                                {{ $t('form.back') }}
                            </button>
                            <button type="submit" class="btn btn-primary">
                                {{ $t('form.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment, { invalid } from 'moment';
Vue.use(moment);
// import 'moment-lunar';
import LunarCalendar from "lunar-calendar";
const _today = new Date();
const _todayComps = {
    year: _today.getFullYear(),
    month: _today.getMonth() + 1,
    day: _today.getDate()
};
export default {
    props: {
        id: String,
        parent: String,
        company_id: String,
    },
    data() {
        return {
            day: _todayComps.day,
            month: _todayComps.month,
            year: _todayComps.year,
            calendar_holidays: [],
            calendar_types: [],
            calendarstl: [],
            page_url_calendarstl: "/api/calendar/calendarstl",
            week_day_labels: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
            calendar: {
                id: "",
                calendar_type_id: "",
                year: 2023,
                title: "",
                company_id: "",
                description: "",
                index: "",
                calendar_details: [],
                calendar_details_del: [],
            },
            companies: [],
            url_goodunits: "/api/calendar/calendarholiday",
            check: {
                checkholiday: true,
            },
            calendar_holidays: [],
            calendar_types: [],
            page_url_calendartypes: "/api/calendar/calendartype",
            page_url_details: "/api/calendar/calendars",
            page_url_company: "/api/category/companies",
            showBottom: false,
            showTop: false,
            calendardetails: {
                id: "",
                calendar_id: "",
                month: "",
                year: "",
                day: "",
                calendar_holiday_id: '',
                work: null,
                index: '',
                new_data: '',
            },
            show_days: true,
            edit_payrequest: false,
            errors: {},
            loading: false,
            edit: false,
            token: "",
            edit_date: false,
            colors:'',
        }
    },

    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px",
        });
        this.getDetails();
        this.fetchGoodunits();
        this.getCalendartypes();
        this.fetCompany();
    },
    watch: {
        'check.checkholiday': function () {
            if (this.check.checkholiday === true) {
                this.calendardetails.calendar_holiday_id = '';
            } else {
                this.calendardetails.calendar_holiday_id = '';
            }
        }
    },
    methods: {  
        change_color(){
            var obj = this.calendar_holidays.find(x => x.id == this.calendardetails.calendar_holiday_id);
            var col=obj != null ? obj.color : '';
            this.colors=col;    
        },
        canceldeleteholiday(day,month){
            let detail = this.calendar.calendar_details;
            let del = this.calendar.calendar_details_del;
            const days = this.calendar.calendar_details_del.find((days) => days.day == day && days.month == month)
            detail.push({ ...days });
            for (let i = 0; i < del.length; i++) {
                for (let j = 0; j < detail.length; j++) {
                    if (del[i].id == detail[j].id) {
                        del.splice(i, 1);
                    }
                }
            }    
        },
        deleteholiday(day,month){
            let detail = this.calendar.calendar_details;
            let del = this.calendar.calendar_details_del;
            const days = this.calendar.calendar_details.find((days) => days.day == day && days.month == month)
            del.push({ ...days });
            for (let i = 0; i < detail.length; i++) {
                for (let j = 0; j < del.length; j++) {
                    if (del[j].id == detail[i].id) {
                        detail.splice(i, 1);
                    }
                }
            }    
        },
        getCurrentYear() {
            let years = [];
            for (var i = 2001; i <= new Date().getFullYear()+10; i++) {
                years.push(i );
            }
            return years;
        },
        CancelAllDelete() {
            let detail = this.calendar.calendar_details;
            let del = this.calendar.calendar_details_del;
            detail.push(...del);
            this.calendar.calendar_details_del = [];
        },
        startDrag(evt, day, month) {
            evt.dataTransfer.dropEffect = 'move'
            evt.dataTransfer.effectAllowed = 'move'
            evt.dataTransfer.setData('day', day)
            evt.dataTransfer.setData('month', month)
        },
        onDrop(evt, list) {
            let detail = this.calendar.calendar_details;
            let del = this.calendar.calendar_details_del;
            const get_day = evt.dataTransfer.getData('day')
            const get_month = evt.dataTransfer.getData('month')
            const days = this.calendar.calendar_details.find((days) => days.day == get_day && days.month == get_month)
            del.push({ ...days });
            for (let i = 0; i < detail.length; i++) {
                for (let j = 0; j < del.length; j++) {
                    if (del[j].id == detail[i].id) {
                        detail.splice(i, 1);
                    }
                }
            }    
        },
        closeform() {
            this.show_days = true;
        },
        ShowDay() {
            $("#addHoliday").modal("show");
        },
        addholidays() {
            if (this.edit_date === false) {
                this.calendar.calendar_details.push({ ...this.calendardetails });
                this.reset();
            } else {   
                this.$set(this.calendar.calendar_details, this.calendardetails.index, { ...this.calendardetails });
                this.reset();
                this.edit_date = false;
            }  
            $("#addHoliday").modal("hide");
        },
        saveDay(day, month, year, id) {
            if (this.check.checkholiday == false && this.calendardetails.calendar_holiday_id != '') {
                const days = this.calendar.calendar_details.find((days) => days.day == day && days.month == month)
                const check_days = days ? days.id : '';
                if (day != 0 && day != undefined && check_days == '') {
                    let index = this.calendar.calendar_details.findIndex(i => {
                        return i.id == id;
                    });
                    this.calendardetails.calendar_id = this.calendar.id;
                    this.calendardetails.day = day;
                    this.calendardetails.month = month;
                    this.calendardetails.year = year;
                    this.calendardetails.index = index;
                    this.calendardetails.id = id;
                    this.calendardetails.new_data = 'new';
                    this.calendar.calendar_details.push({ ...this.calendardetails });
                }
            } else {
                this.check.checkholiday = true;
                if (day != 0 && day != undefined) {
                    const days_edit = this.calendar.calendar_details.find((days) => days.day == day && days.month == month)
                    const check_edit = days_edit ? days_edit.id : '';
                    const check_newdata = days_edit ? days_edit.new_data : '';
                    let index = this.calendar.calendar_details.findIndex(i => {
                        return i.id == check_edit;
                    });
                    this.calendardetails.index = index;
                    if (days_edit == undefined) {
                        this.calendardetails.new_data = 'new';
                        this.calendardetails.id = id;
                    } else {
                        if (check_newdata == 'new') {
                            this.calendardetails.new_data = 'new';
                        } else {
                            this.calendardetails.new_data = '';
                        }             
                        this.calendardetails.id = check_edit;
                    }
                    if (days_edit == undefined) {
                        //Nhap
                        this.edit_date = false;
                    } else {
                        //Cap Nhat
                        this.edit_date = true;
                    }
                    this.calendardetails.calendar_id = this.calendar.id;
                    this.calendardetails.calendar_holiday_id = days_edit ? days_edit.calendar_holiday_id : '';
                    this.calendardetails.day = day;
                    this.calendardetails.month = month;
                    this.calendardetails.year = year;
                    this.show_days = false;
                    $("#addHoliday").modal("show");
                }
            }
        },
        reset() {
            this.calendardetails.month = '';
            this.calendardetails.new_data = '';
            this.calendardetails.calendar_holiday_id = '';
            this.calendardetails.year = '';
            this.calendardetails.index = -1;
            this.calendardetails.work = null;
            this.calendardetails.day = '';
        },
        getFirstWeekDayInMonth(month) {
            return new Date(this.calendar.year, month - 1, 1).getDay() + 1;
        },
        getTotalDaysInMonth(year, month) {
            return new Date(year, month, 0).getDate();
        },
        getClassDayWrapper(day) {
            let day_class = 'day-wrapper';
            if (day.day_of_week === 1) {
                day_class += ' week-sunday-header';
            }
            else {
                day_class += ' week-normalday';
            }
            return day_class;
        },
        getClassDayNumber(day) {
            let day_class = 'day-of-month';
            if (day.is_today) {
                day_class += ' day-current';
            }

            return day_class;
        },
        getClassWeekDayHeader(day_of_week) {
            let header_class = 'week-day-header';
            if (day_of_week == 0) {
                header_class += ' week-sunday-header';
            }
            else {
                header_class += ' week-normalday-header';
            }
            return header_class;
        },
        getWeeksInMonth(year, month) {

            const weeks = [];
            let day_of_month = 0;
            let lunar_day = 0;
            let lunar_month = 0;
            let week_of_year = 0;
            let total_days_in_month = this.getTotalDaysInMonth(year, month);

            for (let week = 1; week <= 6 && day_of_month < total_days_in_month; week++) {
                let current_week = [];

                for (let day_of_week = 1; day_of_week <= 7; day_of_week++) {
                    if (day_of_week >= this.getFirstWeekDayInMonth(month) || day_of_month > 0) {
                        day_of_month++;
                        if (day_of_month > total_days_in_month) break;
                        let date = moment(`${year}-${("0" + (month)).slice(-2)}-${("0" + day_of_month).slice(-2)}`, "YYYY-MM-DD");
                        week_of_year = date.clone().endOf("week").format('W');
                        if(week_of_year=='Invalid date'){
                            let date_end_of_year = moment(`${year-1}-12-31`, "YYYY-MM-DD");
                            week_of_year = date_end_of_year.clone().endOf("week").format('W');
                           
                        }
                        const lunarDate = LunarCalendar.solarToLunar(date.format('YYYY'), date.format('MM'), date.format('DD'));
                        lunar_day = lunarDate.lunarDay;
                        lunar_month = lunarDate.lunarMonth;

                    } else {
                        let date = moment([year, month - 2, 1]).endOf('month');
                        week_of_year = date.clone().endOf("week").format('W');
                        if(week_of_year=='Invalid date'){
                            let date_end_of_year = moment(`${year-1}-12-31`, "YYYY-MM-DD");
                            week_of_year = date_end_of_year.clone().endOf("week").format('W');
                        }
                    }

                    current_week.push({
                        week_of_year,
                        day_of_week,
                        day_of_month: day_of_month,
                        lunar_day,
                        lunar_month,
                        is_today: this.year === year && this.month === month && this.day === day_of_month,
                    });
                }

                for (let i = current_week.length; i < 7; i++) {
                    current_week.push({
                        week_of_year: 0,
                        day_of_week: 0,
                        day_number_in_month: 0,
                        lunar_day: 0,
                        lunar_month: 0,
                        is_today: false,
                    });
                }
                weeks.push(current_week);
            }
            return weeks;
        },
        getCalendartypes() {

            var page_url = this.page_url_calendartypes
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.calendar_types = data.data;
                }).catch(err => {

                    console.log(err);
                });

        },
        fetCompany() {
            var page_url = this.page_url_company;//"/api/category/companies";
        
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {                  
                    this.companies = res.data;
                    console.length(res);
                })
                .catch(err => {
                    console.log(err);
                });
        },
        fetchGoodunits() {
            let vm = this;
            var page_url = this.url_goodunits;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.calendar_holidays = res.data;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        getDetails() {
            if (this.id != null) {
                this.loading = true;
                var page_url = this.page_url_details + "/" + this.id + "/edit";
                fetch(page_url, { headers: { Authorization: this.token } })
                    .then(res => res.json())
                    .then(res => {
                        if (res.statuscode == "403") {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                        if (res.data.success == '1') {
                            this.calendar = res.data;
                            this.calendar.calendar_details_del = [];
                        }
                        this.edit_payrequest = true;
                        this.loading = false;
                    }).catch(err => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        addCalendar() {
            let detail = this.calendar.calendar_details;
            for (let i = 0; i < detail.length; i++) {
                if (detail[i].new_data == 'new') {
                    detail[i].id = null;
                }
            }
            var page_url = this.page_url_details;
            if (this.edit_payrequest === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.calendar),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (data.statuscode == "403") {
                            window.location.href = "/errorpage?statuscode=" + data.statuscode;
                        }
                        this.loading = false;
                        if (data.data.success == '1') {
                            toastr.success(this.$t('form.save_success'), "");
                            window.location.href = '/calendar/calendars';
                        } else {
                            this.errors = data.data.errors;
                            toastr.warning(this.$t('Lỗi'), "");
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        console.log(err);
                    });

            } else {
                //update
                fetch(page_url + "/" + this.calendar.id, {
                    method: "PUT",
                    body: JSON.stringify(this.calendar),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.data.success == '1') {
                            toastr.success(this.$t('form.update_success'), "");
                            window.location.href = '/calendar/calendars';

                        } else {
                            this.errors = data.data.errors;
                            toastr.warning(this.$t('Lỗi'), "");
                        }
                        this.loading = false;
                    })
                    .catch(err => {
                        this.loading = false;

                    });
            }
        },
        showMessage(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };
            toastr.success(message, title);
        },
        hasError(fieldName) {
            return fieldName in this.errors;
        },
        getError(fieldName) {
            return this.errors[fieldName][0];

        },
        clearError(event) {
            Vue.delete(this.errors, event.target.name);
        },
        showError(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };
            toastr.error(message, title);
        },
        clearAllError() {
            this.errors = {};
        },
    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
    },
}




</script>
<style lang="scss" scoped>
.form-group {
    margin-bottom: 1px !important;
}
.cal-cell {
    cursor: pointer;
}
.holiday {
    width: 20%;
}
.dayCantainer {
    height: 82px;
    background: #fff;
    border: 2px solid #ECEEFF;
    border-radius: 8px;
    margin: 0 1px;
    text-align: center;
    width: 53px;
    display: block;
    position: relative;
    overflow: hidden;
    padding: 14px 0;
    box-sizing: border-box;
}
.header-alt {
    padding: 0.5rem 1rem;
}
.title {
    flex-grow: 1;
    font-size: 1.2rem;
}
.week-row {
    display: flex;
}
.day-wrapper {
    width: 14.2857%;
    height: 80px;
    display: flex;
    flex: auto;
    justify-content: center;
    position: relative;
    background-color: white;
    border: 1px solid #f2f2f2;
    padding-top: 3px;
}
td {
    border: 1px solid grey;
}
a {
    text-decoration: none;
    color: rgb(0, 0, 0);
    transition: all 0.5s ease-in-out;
}
a:hover {
    color: rgb(124, 111, 111);
}
.calendar {
    display: flex;
    flex-direction: column;
    width: 100%;
    margin-bottom: 10px;
}
.col-sm-6 {
    flex: 0 0 50%;
    max-width: 100%;
}
.week-days-wrapper {
    display: flex;
    flex: auto;
    height: 50px;
}
.week-day-header {
    border-radius: 8px;
    text-align: center;
    margin-block: auto;
    width: 14.2857%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    font-size: 14pt;
    background-color: white;
    border: 2px solid #f2f2f2;
}
.drop-zone {
    background-color: #eee;
    margin-bottom: 10px;
    padding: 10px;
}
.drag-el {
    background-color: rgb(141, 26, 26);
    margin-bottom: 10px;
    padding: 5px;
}
.week-sunday-header {
    color: #d9001b;
    display: inline-table;
    background: #e7e8e9;
}
.week-normalday-header {
    color: rgb(5, 107, 225);

}
.week-normalday {
    color: #7f7f7f;
    display: inline-table;
}
.week-of-year {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11pt;
    font-weight: bold;
    position: absolute;
    left: 3px;
    top: 0;
    color: #7f7f7f;
    opacity: 0.5;
}
.day-of-month {
    font-weight: bold;
    font-size: 14pt;
}
.event-day {
    color: #a02d2d;
    background-color: rgb(34, 75, 128);
    border-radius: 100%;
    min-width: 28px;
    text-align: center;
}
.day-current {
    color: #fff;
    background-color: rgb(26, 115, 232);
    border-radius: 100%;
    min-width: 28px;
    text-align: center;
}
.lunar-day-of-month {
    font-size: 10pt;
    position: absolute;
    right: 2px;
    bottom: 0;
    opacity: 0.5;
    text-align: right;
}
.calendar-header {
    text-align: center;
    background: #5170d5;
    height: 35px;
    line-height: 25px;
    color: #fff;
    font-size: 14pt;
    padding: 5px 0;
    position: relative
}
.calendar-wrapper {
    border: 1px solid #bcdfbb;
}
.col-md-3{
  flex: 33.333333%;
  max-width: 100%;
}

.fixed-top{
    position: fixed;
  top: 5%;
  left: 50%;
  transform: translate(-50%, -50%);   
}
</style>
