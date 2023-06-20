<template>
  <div>
    <div v-if="hasAdminMenu" class="nav-item">
      <div class="nav-link">
        <div class="form-check">
          <input id="checkAdmin" type="checkbox" v-model="is_show_admin_menus" class="form-check-input">
          <label class="form-checkbox-label" for="checkAdmin">CHỈ HIỆN MENU ADMIN</label>
        </div>
      </div>
    </div>
    <ul class="nav nav-pills nav-sidebar flex-column nav-legacy" data-widget="treeview" role="menu"
      data-accordion="false">
      <li v-for="menu in getFilterMenus" :key="menu.id" :class="getItemClass(menu)" :style="getItemStyle(menu)">
        <strong v-if="menu.link == '#' && menu.icon == ''"> {{ menu.title }}</strong>

        <a v-else :class="getLinkClass(menu)" style="cursor: pointer">
          <i v-if="menu.icon" :class="'nav-icon ' + menu.icon"></i>
          <p>
            {{ $t(menu.title) }}
            <i v-if="menu.has_childrens" class="fas fa-angle-right right"></i>
            <span v-if="hasMenuPendingCount(menu)" class="badge badge-danger right">
              {{ getMenuPendingCount(menu) }}
            </span>
          </p>
        </a>
        <side-menu-childrens v-if="menu.has_childrens" :level="1" :menus="menu.childs" :is_opening="getIsOpening(menu)"
          :menu_parent="menu" :menu_current="menu_current" :menu_current_root="menu_current_root"
          :pending_counts="pending_counts" />
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    menus: Array,
    menu_current_root: Object,
    menu_current: Object,
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.getPendingCount();
  },
  data() {
    return {
      pending_counts: {},
      is_show_admin_menus: false
    };
  },
  computed: {
    getFilterMenus() {
      let is_show_admin_menus = this.is_show_admin_menus;
      console.log(this.menus);
      return this.menus.filter(function (menu) {
        if (is_show_admin_menus) {
          return menu.is_admin_menu;
        }
        return true;
      });
    },
    hasAdminMenu() {
      return this.menus.some((menu) => menu.is_admin_menu);
    }
  },
  methods: {
    getPendingCount() {
      fetch("/api/get_menu_statistics", {
        method: "get",
        headers: {
          Authorization: this.token,
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          this.pending_counts = data.pending_counts;
        })
        .catch(err => {
          console.log(err);
        });
    },
    getItemClass(menu) {
      if (menu.link == '#' && menu.icon == '') {
        return 'nav-header'
      }
      else {
        var item_class = 'nav-item';
        if (!menu.has_childrens) {
          item_class += ' has-treeview';
        }
        else if (menu.id == this.menu_current_root.id) {
          item_class += ' menu-open';
        }
        return item_class;
      }
    },
    getIsOpening(menu) {
      if (menu.link == '#' && menu.icon == '') {
        return false;
      }
      else if (menu.id == this.menu_current_root.id) {
        return true;
      }
      return false;
    },
    getItemStyle(menu) {
      if (menu.link == '#' && menu.icon == 0) {
        return 'text-transform: uppercase';
      }
      return '';
    },
    getLinkClass(menu) {
      var item_class = 'nav-link';
      if (menu.id == this.menu_current_root.id ||
        menu.id == this.menu_current.id ||
        (menu.left > this.menu_current_root.left && menu.right < this.menu_current_root.right)
      ) {
        item_class += ' active';
      }
      return item_class;
    },
    hasMenuPendingCount(menu) {
      if (this.pending_counts) {
        return Object.keys(this.pending_counts).indexOf(menu.id.toString()) != -1;
      }
    },
    getMenuPendingCount(menu) {
      return this.pending_counts[menu.id];
    }
  },
};
</script>
