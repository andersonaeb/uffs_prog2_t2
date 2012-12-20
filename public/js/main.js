Main = {
    init: function() 
    {
        document.getElementsByName('s')[0].init = 'Song title, artist name';
        
        document.getElementsByName('s')[0].onfocus = function() {
            if(this.value == this.init) {
                this.value = '';  
            }
        };
        
        document.getElementsByName('s')[0].onblur = function() {
            if(this.value == '') {
                this.value = this.init;  
            }
        };
        
    }
}