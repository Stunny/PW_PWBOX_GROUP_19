$(document).ready(()=>{


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
      }
    }
  });

  var centerContent = new Vue({
    el: '#rows',
    template: '',
    data: {
      
    },
    methods:{

    }
  });

  dashLeftTree.setTree([
    {name: "Folder1", children:[{name:"SubFolder1", children:[{name:"SubSubFolder1", children:[]}]}, {name:"SubFolder2", children:[]}]},
    {name: "Folder2", children: []}
  ]);
  

});
