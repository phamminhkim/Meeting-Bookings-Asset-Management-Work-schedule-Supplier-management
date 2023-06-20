/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
window.Vue.use(VueRouter);


import router from './router.js'

Vue.component('demo-sgp', require('./components/demo.vue').default);


import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated.js';
Vue.use(VueInternationalization);
const i18n = new VueInternationalization({
    locale: document.head.querySelector('meta[name="locale"]').content,
    messages: Locale
});
Vue.filter('formatDayofWeek', function(value) {
    if (value) {
        var dateformat = "DD";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[0];
    }
});
Vue.filter('formatDayofWeeka', function(value) {
    if (value) {
        var dateformat = "DD";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[1];
    }
});
Vue.filter('formatDayofWeek_3', function(value) {
    if (value) {
        var dateformat = "DD";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[2];
    }
});
Vue.filter('formatDayofWeek_4', function(value) {
    if (value) {
        var dateformat = "DD";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[3];
    }
});
Vue.filter('formatDayofWeek_5', function(value) {
    if (value) {
        var dateformat = "DD";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[4];
    }
});
Vue.filter('formatDayofWeek_6', function(value) {
    if (value) {
        var dateformat = "DD";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[5];
    }
});
Vue.filter('formatDayofWeek_7', function(value) {
    if (value) {
        var dateformat = "DD";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[6];
    }
});
Vue.filter('formatDayofmonth', function(value) {
    if (value) {
        var dateformat = "MM";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[0];
    }
});
Vue.filter('formatDayofmonth_1', function(value) {
    if (value) {
        var dateformat = "MM";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[1];
    }
});
Vue.filter('formatDayofmonth_2', function(value) {
    if (value) {
        var dateformat = "MM";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[2];
    }
});
Vue.filter('formatDayofmonth_3', function(value) {
    if (value) {
        var dateformat = "MM";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[3];
    }
});
Vue.filter('formatDayofmonth_4', function(value) {
    if (value) {
        var dateformat = "MM";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[4];
    }
});
Vue.filter('formatDayofmonth_5', function(value) {
    if (value) {
        var dateformat = "MM";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[5];
    }
});
Vue.filter('formatDayofmonth_6', function(value) {
    if (value) {
        var dateformat = "MM";
        var date = moment(String(value)).isoWeek(value||1).startOf("week"), value=7, result=[];
            while(value--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result[6];
    }
});
Vue.filter('formatWeek', function(value) {
    if (value) {
        var m = moment(value),
            weekDay = m.day(),
            yearDay = m.dayOfYear(),
            year = m.year(),
            count = 0;
            m.startOf('month');
        while (m.dayOfYear() <= yearDay && m.year() == year) {
            if (m.day() == weekDay) {
                count++;
            }
            m.add('days',1);
        }

        return count;
    }
});
import moment from 'moment';
Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD-MM-YYYY')
    }
});
Vue.filter('formatDB', function(value) {
    if (value) {
        return moment(String(value)).format('DD-MM-YYYY')
    }
});
Vue.filter('formatWeekYear', function(value) {
    if (value) {
        return moment(String(value)).format('WW')
    }
});
Vue.filter('formatDateTime', function(value) {
    if (value) {
        return moment(String(value)).format('DD/MM/YYYY HH:mm')
    }
});
Vue.filter('formatDateDB', function(value) {
    if (value) {
        return moment(String(value)).format('YYYY-MM-DD')
    }
});
Vue.filter('formatDateTimeDB', function(value) {
    if (value) {
        return moment(String(value)).format('YYYY-MM-DD hh:mm')
    }
});
Vue.filter('formatDT', function(value) {
    if (value) {
        return moment(String(value)).format('DD-MM-YYYY hh:mm:ss')
    }
});
Vue.filter('ago', function(value) {
    if (value) {
        return moment(String(value)).fromNow();
    }
});
import numeral from 'numeral';
import numFormat from 'vue-filter-number-format';

Vue.filter('numFormat', numFormat(numeral));

import Select2 from 'v-select2-component';
Vue.component('Select2', Select2);



import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
//  import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
import JsonExcel from 'vue-json-excel'

Vue.component('download-excel', JsonExcel)

import VueNumeric from 'vue-numeric'
Vue.use(VueNumeric)

Vue.filter("formatNumber_amount", function (value) {
    if (value) {
        return numeral(String(value)).format("0,0");
    }
    // displaying other groupings/separators is possible, look at the docs
  });

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('notification', require('./components/shared/Notification.vue').default);
Vue.component('notification-list', require('./components/notification/NotificationList.vue').default);
Vue.component('notification-new', require('./components/notification/NotificationMenuTop.vue').default);
Vue.component('language-menu-top', require('./components/menu/LanguageMenuTop.vue').default);
Vue.component('side-menu', require('./components/menu/SideMenu.vue').default);
Vue.component('side-menu-childrens', require('./components/menu/SideMenuChildrens.vue').default);
Vue.component('hotline-contact', require('./components/menu/HotLineContact.vue').default);

Vue.component('changlanguage', require('./components/shared/ChangeLanguage.vue').default);
Vue.component('list-choose', require('./components/shared/ListChoose.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));
//category
Vue.component('company', require('./components/category/hr/Company.vue').default);
Vue.component('department', require('./components/category/hr/Department.vue').default);
Vue.component('workshop', require('./components/category/hr/Workshop.vue').default);
Vue.component('party', require('./components/category/hr/Party.vue').default);
Vue.component('directtype', require('./components/category/hr/DirectType.vue').default);
Vue.component('employee', require('./components/category/hr/Employee.vue').default);
Vue.component('employeetype', require('./components/category/hr/EmployeeType.vue').default);
Vue.component('employmenttype', require('./components/category/hr/EmploymentType.vue').default);
Vue.component('jobtitle', require('./components/category/hr/JobTitle.vue').default);
Vue.component('resigntype', require('./components/category/hr/ResignType.vue').default);

Vue.component('team', require('./components/category/team/TeamList.vue').default);
Vue.component('position', require('./components/category/Position.vue').default);
Vue.component('position-app', require('./components/category/PositionApprove.vue').default);
Vue.component('team-assign', require('./components/category/team/TeamAssign.vue').default);
Vue.component('team-assign-usercc', require('./components/category/team/TeamAssignUserCC.vue').default);
Vue.component('service-category', require('./components/category/ServiceCategory.vue').default);
Vue.component('parequest-type', require('./components/category/PayrequestType.vue').default);
Vue.component('vendor', require('./components/category/Vendor.vue').default);
Vue.component('product', require('./components/category/Product.vue').default);
Vue.component('hrconfig-type', require('./components/category/HrConfigType.vue').default);
Vue.component('hrconfig-price', require('./components/category/HrConfigPrice.vue').default);
Vue.component('module', require('./components/category/Module.vue').default);
Vue.component('uniqueid', require('./components/category/UniqueId.vue').default);
Vue.component('workflow-type', require('./components/category/WorkflowType.vue').default);
Vue.component('workflow-job-type', require('./components/category/WorkflowJobType.vue').default);
Vue.component('workflow-variable', require('./components/category/WorkflowVariable.vue').default);
Vue.component('workflow-condition', require('./components/category/WorkflowCondition.vue').default);
//
Vue.component('profile', require('./components/profile-user/ProfileDetail.vue').default);
Vue.component('contract', require('./components/payment/contract/ContractList.vue').default);
Vue.component('contract-create-one', require('./components/payment/contract/ContractCreate.vue').default);
Vue.component('contract-detail', require('./components/payment/contract/ContractDetail.vue').default);
Vue.component('contract-plan-payment-run', require('./components/payment/contract/ContractPlanPaymentRun.vue').default);
Vue.component('contract-liquidation', require('./components/payment/contract/ContractLiquidation.vue').default);
Vue.component('approve-form', require('./components/approve/ApproveForm.vue').default);
Vue.component('approve-add-user', require('./components/approve/ApproveAddUser.vue').default);
 

//Car
Vue.component('car-error', require('./components/category/CarError.vue').default);
Vue.component('type-car', require('./components/category/TypeCar.vue').default);
Vue.component('standard', require('./components/category/Standard.vue').default);
Vue.component('system-car', require('./components/car/SystemCarList.vue').default);
Vue.component('system-car-create', require('./components/car/SystemCarCreate.vue').default);
Vue.component('system-car-detail', require('./components/car/SystemCarDetail.vue').default);
Vue.component('approve-systemcar', require('./components/approvewf/ApproveSystemCar.vue').default);
Vue.component('approve-productcar', require('./components/approvewf/ApproveProductCar.vue').default);
Vue.component('car-statistical', require('./components/car/CarStatistical.vue').default);
Vue.component('car-log', require('./components/car/CarLog.vue').default);
//document
Vue.component('document-list', require('./components/document/DocumentList.vue').default);
Vue.component('document-create', require('./components/document/DocumentCreate.vue').default);
Vue.component('document-detail', require('./components/document/DocumentDetail.vue').default);
//hr record
Vue.component('record-list', require('./components/productivity/record/RecordList.vue').default);
Vue.component('record-detail', require('./components/productivity/record/RecordDetail.vue').default);
Vue.component('hrdocument-detail', require('./components/productivity/document/HrDocumentDetail.vue').default);
Vue.component('hrfinal-detail', require('./components/productivity/document/HrFinalDetail.vue').default);
//hr add mark
Vue.component('hraddmark-list', require('./components/productivity/addmark/HrAddMarkList.vue').default);
//hr day off
Vue.component('hrdayoff-list', require('./components/productivity/dayoff/HrDayoffList.vue').default);
//hr salary info
Vue.component('hrsalaryinfo-list', require('./components/productivity/salaryinfo/HrSalaryInfoList.vue').default);
//Work - workflow
Vue.component('work-list', require('./components/work/workflow/runtime/WorkList.vue').default);
Vue.component('work-create', require('./components/work/workflow/runtime/WorkCreate.vue').default);
Vue.component('work-detail', require('./components/work/workflow/runtime/WorkDetail.vue').default);
//Work - define
Vue.component('workdefine-list', require('./components/work/workflow/define/WorkDefineList.vue').default);
Vue.component('workdefine-create', require('./components/work/workflow/define/WorkDefineCreate.vue').default);
Vue.component('workdefine-detail', require('./components/work/workflow/define/WorkDefineDetail.vue').default);
//Issue
Vue.component('issue-list', require('./components/issue/IssueList.vue').default);
Vue.component('issue-create', require('./components/issue/IssueCreate.vue').default);
Vue.component('issue-detail', require('./components/issue/IssueDetail.vue').default);
//Test
Vue.component('test-detail', require('./test/TestDetail.vue').default);

//payrequest
Vue.component('pay-request', require('./components/payment/request/PayRequestList.vue').default);
Vue.component('pay-request-create', require('./components/payment/request/PayRequestCreate.vue').default);
Vue.component('pay-request-detail', require('./components/payment/request/PayRequestDetail.vue').default);
Vue.component('pay-request-statistics', require('./components/payment/request/PayRequestStatistics.vue').default);
Vue.component('loading', require('./components/shared/Loading.vue').default);
Vue.component('approve-payment', require('./components/approve/ApprovePayment.vue').default);
Vue.component('approve-document', require('./components/approve/ApproveDocument.vue').default);
Vue.component('approve-productivity', require('./components/approve/ApproveProductivity.vue').default);
Vue.component('approve-price', require('./components/approve/ApprovePrice.vue').default);
Vue.component('approve-step', require('./components/category/ApproveStep.vue').default);
Vue.component('approve-limit', require('./components/category/ApproveLimit.vue').default);
Vue.component('approve-routing', require('./components/category/ApproveRouting.vue').default);
Vue.component('document-type', require('./components/category/DocumentType.vue').default);
Vue.component('document-group', require('./components/category/DocumentGroup.vue').default);
Vue.component('docbrowser-type', require('./components/category/DocbrowserType.vue').default);
Vue.component('group', require('./components/category/group/GroupList.vue').default);
Vue.component('user-list', require('./components/category/UserList.vue').default);
Vue.component('permission-list', require('./components/category/PermissionList.vue').default);
Vue.component('role-list', require('./components/category/RoleList.vue').default);
Vue.component('Wfmain', require('./components/category/workflow/WfmainList.vue').default);
Vue.component('workflow-step', require('./components/category/workflow/WorkFlowStep.vue').default);
Vue.component('wftuserype', require('./components/category/workflow/WfusertypeList.vue').default);
Vue.component('wfapptype', require('./components/category/workflow/WftappypeList.vue').default);
Vue.component('wfstepdetail', require('./components/category/workflow/WfstepdetailList.vue').default);
Vue.component('causes-list', require('./components/car/CausesList.vue').default);
Vue.component('create-fast-process-dialog', require('./components/car/FastProcessDialog.vue').default);
Vue.component('create-causes-dialog', require('./components/car/CreateCausesDialog.vue').default);
Vue.component('create-monitor-dialog', require('./components/car/CreateMonitorDialog.vue').default);
Vue.component('create-result-dialog', require('./components/car/CreateResultDialog.vue').default);
Vue.component('group-assign', require('./components/category/group/GroupAssign.vue').default);
Vue.component('create-date-limit-dialog-car', require('./components/car/CreateDateLimitDialog.vue').default);
Vue.component('date-limit-list-car', require('./components/car/DateLimitList.vue').default);
Vue.component('car-share', require('./components/car/CarShare.vue').default);
Vue.component('car-assign-dialog', require('./components/car/CarAssign.vue').default);
Vue.component('car-assign-list', require('./components/car/CarAssignList.vue').default);
//Customer Debit causes
Vue.component('create-event-dialog', require('./components/reminder/CreateEventDialog.vue').default);
Vue.component('reminder-list', require('./components/reminder/ReminderList.vue').default);
Vue.component('modal-simple', require('./components/shared/ModalSimple.vue').default);
//shared
Vue.component('shared-dialog', require('./components/shared/SharedDialog.vue').default);
Vue.component('shared-list', require('./components/shared/SharedList.vue').default);
//Timeline
Vue.component('timeline-list', require('./components/timeline/TimelineList.vue').default);
//Create new Menu
Vue.component('create-new-menu', require('./components/shared/CreateNewMenu.vue').default);
//Modal
//Price Approve Request
Vue.component('price-approve-request', require('./components/managerprice/AppPriceList.vue').default);
Vue.component('price-approve-request-create', require('./components/managerprice/AppPriceCreate.vue').default);
Vue.component('price-approve-request-detail', require('./components/managerprice/AppPriceDetail.vue').default);
Vue.component('price-approve-request-review', require('./components/managerprice/AppPriceReview.vue').default);
Vue.component('price-approve-request-review-vendor', require('./components/managerprice/AppPriceReviewVendors.vue').default);
Vue.component('price-approve-request-review-spec-vendor', require('./components/managerprice/AppPriceReviewSpecVendors.vue').default);

//Storage localtion - sloc
Vue.component('goodtypes', require('./components/sloc/Goodtypes.vue').default);
Vue.component('goodunits', require('./components/sloc/Goodunits.vue').default);
Vue.component('goodtypes_test', require('./components/sloc/Goodtypestest.vue').default);
Vue.component('goods',require('./components/sloc/Goods.vue').default); // 
Vue.component('goods-create',require('./components/sloc/GoodsCreate.vue').default); // Create goods
Vue.component('docus-good',require('./components/sloc/GoodDocus.vue').default); // Create Docus
Vue.component('docus-create',require('./components/sloc/GoodCreate.vue').default);
Vue.component('docus-details',require('./components/sloc/GoodDetails.vue').default);
Vue.component('goods-list',require('./components/sloc/GoodsList.vue').default);
Vue.component('goods-details',require('./components/sloc/GoodsDetails.vue').default);

//Storage Location - Asset
Vue.component('asset-list',require('./components/asset/Assets.vue').default);
Vue.component('asset-stock',require('./components/asset/AssetStock.vue').default);
Vue.component('asset-type',require('./components/asset/AssetType.vue').default);
Vue.component('asset-change',require('./components/asset/transaction/AssetChange.vue').default);
Vue.component('add-change',require('./components/asset/transaction/AddChange.vue').default);
Vue.component('add-change-repair',require('./components/asset/transaction/AddChangeRepair.vue').default);

Vue.component('detail-change',require('./components/asset/DetailChange.vue').default);
Vue.component('asset-setting',require('./components/asset/AssetSetting.vue').default);
Vue.component('asset-group',require('./components/asset/AssetGroup.vue').default);
Vue.component('asset-inventario',require('./components/asset/AssetInventario.vue').default);
Vue.component('inventario-product',require('./components/asset/InventarioProduct.vue').default);
Vue.component('inventario-detail',require('./components/asset/inventarioDetail.vue').default);
Vue.component('inventario-success',require('./components/asset/InventarioSuccess.vue').default);
Vue.component('asset-members',require('./components/asset/AssetMembers.vue').default);
Vue.component('asset-report',require('./components/asset/AssetReport.vue').default);
Vue.component('asset-status',require('./components/asset/AssetStatus.vue').default);
Vue.component('asset-my',require('./components/asset/AssetMy.vue').default);
Vue.component('asset-confirm',require('./components/asset/AssetConfirm.vue').default);
Vue.component('asset-record',require('./components/asset/AssetTransferRecord.vue').default);


//Storage Location - Calendar
Vue.component('calendartype-create', require('./components/calendar/CalendarType.vue').default);
Vue.component('calendarholiday-create', require('./components/calendar/CalendarHoliday.vue').default);
Vue.component('calendar', require('./components/calendar/Calendars.vue').default);
Vue.component('calendar-create', require('./components/calendar/CalendarCreate.vue').default);
Vue.component('calendar-details', require('./components/calendar/CalendarDetails.vue').default);

// Live Q&A

Vue.component('page-question', require('./components/qa/PageQuestion.vue').default);
Vue.component('page-answer', require('./components/qa/PageAnswer.vue').default);

//Client
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component('myticket', require('./components/Ticket.vue').default);
Vue.component('myticket-detail', require('./components/TicketDetail.vue').default);
Vue.component('myticket-item', require('./components/TicketItem.vue').default);
Vue.component('myticket-detail', require('./components/TicketDetail.vue').default);
Vue.component('myticket-report', require('./components/TicketReport.vue').default);
Vue.component('myticket-reportlist', require('./components/TicketReportList.vue').default);
Vue.component('myticket-rating', require('./components/TicketRating.vue').default);
Vue.component('rating', require('./components/Utilities/Rating.vue').default);
Vue.component('ticketcommentlist', require('./components/TicketCommentList.vue').default);
Vue.component('ticket-comment', require('./components/TicketComment.vue').default);
Vue.component('myticket-assign', require('./components/TicketAssign.vue').default);
Vue.component('comment-list', require('./components/comment/Comment.vue').default);

Vue.component('search-textbox', require('./components/Utilities/SearchingTextbox.vue').default);
//Dashboard
Vue.component('main-dashboard', require('./components/dashboard/DashboardMain.vue').default);
Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component('audit-users', require('./components/admin/AuditUsers.vue').default);
Vue.component('audit-roles', require('./components/admin/AuditRoles.vue').default);


Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

import VueExcelEditor from 'vue-excel-editor'

Vue.use(VueExcelEditor)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('ckeditor', require('./components/Ckeditor.vue').default);
import CKEditor from 'ckeditor4-vue';
Vue.use(CKEditor);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//khởi tạo thông báo: góc bên phải - bên dưới
toastr.options = {
    positionClass: "toast-bottom-right"
};



const app = new Vue({
    el: '#app',
    router,
    i18n,

    data() {
        return {
            // message :"it's Vue time",


        }
    },
    ready() {

    },

});