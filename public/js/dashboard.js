$(document).ready(()=>{

  //-------Inicializaciones de modulos Vue
  var dashLeftTree = new Vue({
    el: '#dashLeftTree',
    data: {
      tree: []
    },
    template: `
      <span>
        <left-list-item
          v-for="item in tree"
          :name="item.name"
          :children="item.children"
          :key="'treeItem-'+item.name"
        >
        </left-list-item>
      </span>  
    `,
    methods: {
      setTree: function(arrayTree){
        this.tree = arrayTree;
      }
    }
  });

  var pathTitle = new Vue({
    el: '#pathTitle',
    template: `<span>{{ title }}</span>`,
    data: {
      title: "My Files"
    },
    methods: {
      setTitle: function(title){
        this.title = title;
      },
      getTitle: function(){
        return this.title;
      }
    }
  });

  var centerContent = new Vue({
    el: '#rows',
    template: `
      <span>
        <folder-item-row
          v-for="row in rows"
          :items="row.items"
          :key="rowkey(row.items)"
        >
        </folder-item-row>
      </span>
    `,
    data: {
      rows: []
    },
    methods:{
      setRows: function(rows){
        this.rows = rows;
      },
      rowKey: function(items){
          var key = "";
          for(item in items){
            key += item.filename + "-"
          }

          return key;
      }
    }
  });

  var leftNav = new Vue({
    el: '#topSideList',
    data:{
      filesSelected: true,
      profileSelected: false,
      settingsSelected: false
    },
    methods:{
      tabFiles: function(){
        if(this.filesSelected) return;

        this.filesSelected = true;
        this.profileSelected = false;
        this.settingsSelected = false;
        pathTitle.setTitle('My Files');

        loadDashboardContent();
      },
      tabProfile: function(){
        if(this.profileSelected) return;

        this.filesSelected = false;
        this.profileSelected = true;
        this.settingsSelected = false;
        pathTitle.setTitle('My profile');

        loadDashboardContent();
      },
      tabSettings: function(){
        if(this.settingsSelected) return;

        this.filesSelected = false;
        this.profileSelected = false;
        this.settingsSelected = true;
        pathTitle.setTitle('Settings');

        loadDashboardContent();
      }
    }
  });

  function loadDashboardContent(){
    switch(pathTitle.getTitle()){
        case "My Files":
  
        break;
  
        case "My Profile":
  
        break;
  
        case "Settings":
  
        break;
  
        default:
    }
  }

  //------------------------Script principal de la pagina
  
  loadDashboardContent();
});
