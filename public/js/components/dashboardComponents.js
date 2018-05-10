Vue.component('folder-item', {
  props: ['filename', 'extension', 'itemType'],
  data: function(){
    return {}
  },
  template: `
  <div class="four wide center aligned column">
    <div class="dashItem">
      <img class="ui image" src="media/{{itemType}}.png">
      <h4 class="ui grey header">
        {{filename}}.{{extension}}
      </h4>
    </div>
  </div>
  `
});

Vue.component('folder-item-row', {
  props: ['items'],
  data: function(){
    return {}
  },
  template: `
    <div class="row">
      <span>
        <content-item
          v-for="item in items"
          :filename="item.filename"
          :extension="item.extension"
          :itemType="item.type"
          :key="item.filename+'.'+item.extension"
        >
        </content-item>
      </span>
    </div>
  `
});


Vue.component('left-list-item', {
  props: ['name', 'children'],
  data: function(){
    return{};
  },
  template: `
  <div class="item"
    v-bind:id="'treeItem-'+name">
    <div class="content">
      <a class="item">{{name}}</a>
      <span>
        <left-list-item
          v-for="item in children"
          :name="item.name"
          :key="'treeItem-'+name"
          :children="item.children"
        >
        </left-list-item>
      </span> 
    </div>
  </div>
  `
});
