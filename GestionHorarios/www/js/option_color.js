document.addEventListener('DOMContentLoaded', function() {
    const selectElements = document.querySelectorAll('select[name^="modules"]');
    
    selectElements.forEach(select => {
        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const color = selectedOption.getAttribute('data-color');
            
            // Depuración: Ver el color obtenido
            console.log('Color del módulo seleccionado: ', color);
            
            // Obtener la celda (td) que contiene este select
            const td = this.closest('td');
            
            // Cambiar el color de fondo de la celda (td)
            if (color) {
                td.style.backgroundColor = color;
                
                // Cambiar el color de fondo del select
                this.style.backgroundColor = color;
                this.style.color='white';
            } else {
                td.style.backgroundColor = ''; // Restaurar a color por defecto si no hay color
                this.style.backgroundColor = ''; // Restaurar el color de fondo del select
            }
        });
    });
});
