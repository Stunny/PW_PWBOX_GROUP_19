Vue.component('content-item', {
  props: ['filename', 'extension', 'itemType'],
  data: function(){
    return {

    }
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
