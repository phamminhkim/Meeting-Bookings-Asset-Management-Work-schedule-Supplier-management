<template>
  <div>
    <div class="container">
      <div class='control'>
        <div id='app'>
          <div class='header'>
            <div class="title" style="font-weight: 700;text-align:center; color:red; font-size:25px">
              <i>Lịch làm việc {{ $t(getGoodDetailsValueName(calendars.calendar_type_id)) }} năm {{ calendars.year }}</i><br>
            </div>
          </div>
          <div class="header-alt"> <div class="col-sm-2">
            <p style="font-size: 15px;"><b>Ghi chú:</b> </p></div>
            <div class="row">
              <div class="col-sm-12">
                <div class="row">
                  <div v-for="(goodl, index) in calendar_holidays " v-bind:key="index" style="width:20%;">
                    <div class="col-sm-6">
                      <div class="row">
                        <div class="col-sm-2" :style="{ 'background-color': goodl.color }"
                          style="background:#71d731;height: 20px; margin-top: 5px">
                        </div>
                        <div class="col-sm-10">
                          <p style="margin-top:7px;font-size:13px;"> {{ goodl.name }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-2">
              <div style="margin-top:15px;font-size: 15px;"><strong>Công ty: </strong>
                {{ getCompanyName(calendars.company_id) }}-<span>{{ calendars.company_id }}</span> </div>
              <div style="margin-top:15px;font-size: 15px;"><strong>Mô tả: </strong> {{ calendars.description }} </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12" v-for="month in 12" :key="year + '-' + month">
              <div class='calendar'>
                <div class="calendar-header">
                  <i style="font-weight: 700; font-size:17px; margin-bottom: 5px;"> Tháng
                    {{ month }}</i>
                </div>
                <div class="calendar-wrapper">
                  <div class='week-days-wrapper'>
                    <div :class="getClassWeekDayHeader(index)" v-for='(weekday, index) in week_day_labels'
                      :key="year + '-' + month + '-' + weekday">
                      <span> {{ weekday }}</span>
                    </div>
                  </div>
                  <div class='week-row' v-for='(current_week, week_index) in getWeeksInMonth(calendars.year, month)'
                    :key="year + '-' + month + '-' + week_index">
                    <div :class='getClassDayWrapper(day)' v-for='(day, day_index) in current_week'
                      :key="year + '-' + month + '-' + week_index + '-' + day_index" v-b-tooltip.html.v-danger
                      class="dayCantainer">
                      <div>
                        <div v-if="day.day_of_month > 0" :class="getClassDayNumber(day)"> {{
                          day.day_of_month
                        }}
                        </div>
                        <div v-for="catl in calendars.calendar_details">
                          <div style="border-radius: 10px;" class="cal-cell"
                            v-if="catl.day == day.day_of_month && catl.month == month">
                            <div style="color:white;font-size: 10px;border-radius: 2px;"
                              v-for="holiday in calendar_holidays">
                              <div class="mb-1" :style="'background-color:' + holiday.color + ';border-radius:3px;'"
                                v-if="holiday.id == catl.calendar_holiday_id">
                                <span>{{ holiday.name }}</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div v-if="day.day_of_week == 1" class="week-of-year">{{ day.week_of_year }}</div>
                        <div v-if="day.lunar_day > 0" class="lunar-day-of-month">{{ day.lunar_day }}{{
                          day.lunar_day == 1 ? '/' +
                            day.lunar_month : ''
                        }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import moment from 'moment';
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
    notification_id: String,
    showicon: Boolean,
    object: Object,
    type: String,
  },
  data() {
    return {
      calendars: {
        id: "",
        calendar_type_id: "",
        year: "",
        title: "",
        description: "",
        index: "",
        calendars: [],
        calendar_details: [],
      },
      page_url_calendar_holiday: "/api/calendar/calendarholiday",
      calendar_holidays: [],
      calendar_types: [],
      page_url_calendar_types: "/api/calendar/calendartype",
      page_url_calendars: "/api/calendar/calendars",
      loading: false,
      companies: [],
      page_url_company: "/api/category/companies",
      token: "Bearer " + window.Laravel.access_token,
      day: _todayComps.day,
      month: _todayComps.month,
      year: _todayComps.year,
      week_day_labels: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
    }
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    $("#spinner-opts small").css({
      display: "inline-block",
      width: "60px",
    });
    this.fetchHolidays();
    this.fetchCalendarTypes();
    this.fetchCalendar();
    this.fetCompany();
  },
  methods: {
    getFirstWeekDayInMonth(month) {
      return new Date(this.calendars.year, month - 1, 1).getDay() + 1;
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
    }
    ,
    getCompanyName(company_id) {
      var obj = this.companies.find(x => x.id == company_id);
      return obj ? obj.name : '';
    },
    fetCompany() {
      var page_url = this.page_url_company;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.companies = res.data;
        })
        .catch(err => {
          console.log(err);

        });
    },
    getGoodDetailsValueName(id) {
      var obj = this.calendar_types.find(x => x.id == id);
      return obj ? obj.name : '';
    },
    fetchCalendarTypes() {
      var page_url = this.page_url_calendar_types
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
    fetchHolidays() {
      let vm = this;
      var page_url = this.page_url_calendar_holiday;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.calendar_holidays = res.data;
        }).catch(err => {
          console.log(err);
          this.loading = false;

        });
    },
    fetchCalendar() {
      if (this.id != null) {
        this.loading = true;
        var page_url = this.page_url_calendars + "/" + this.id + "?notification_id=" + this.notification_id;
        fetch(page_url, { headers: { Authorization: this.token } })
          .then(res => res.json())
          .then(res => {
            if (res.statuscode == "403") {
              window.location.href = "/errorpage?statuscode=" + res.statuscode;
            }
            if (res.data.success == '1') {
              this.calendars = res.data;
            }
            this.loading = false;
          }).catch(err => {
            this.loading = false;
            console.log(err);
          });
      }
    },

  },
  computed: {
  },
}
</script>
<style  lang="scss" scoped>.form-group {
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
  text-align: center;
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
  left: 0;
 
}
</style>
