import { Tickets } from "./Tickets.js";

document.addEventListener('DOMContentLoaded', function(){
    const buyTickets = document.querySelector(".buy-tickets");
    const burgerMenu = document.querySelector(".burger-menu");
    const main = document.querySelector('main');
    const body = document.querySelector('body');
    const dysButton = document.querySelector('#dys-font');
    const userDelete = document.querySelector('.user-delete');
    // Check if buy tickets form is not empty
    if(buyTickets){
        const select = document.querySelectorAll(".buy-tickets select");
        const par = document.querySelector(".tickets-total p");
        const hidePrice = document.querySelector("#tickets-total-input");
        const button = document.querySelector(".buy-tickets button");
        const lastName = document.querySelector("#last_name");
        const firstName = document.querySelector('#first_name');
        const email = document.querySelector('#email');
        const tickets = new Tickets(select);
        button.disabled = true;
        let ticketsTotal = false;
        if(button.disabled === true){
            button.style.backgroundColor = "#e0e0e0";
            button.style.color = "#3d3d3d";
        }

        select.forEach((element) =>{
            element.addEventListener('change', (e) =>{
                par.innerText = "Total : " + tickets.total() + "€";
                hidePrice.value = tickets.total();
                if(tickets.total()>1){
                    ticketsTotal = true;
                }
            })
        })

        buyTickets.addEventListener('input', ()=>{
            if(ticketsTotal === true && lastName.value && firstName.value && email.value){
                button.disabled = false;
                button.style.backgroundColor = "#FFE500";
                button.style.color = "#020403";
            }
            if(button.disabled === true){
                button.style.backgroundColor = "#e0e0e0";
                button.style.color = "#3d3d3d";
            }
        })
    }
    // Show and hide burger nav
    if(burgerMenu){
        const headerNav = document.querySelector(".header-nav");
        burgerMenu.addEventListener('click', ()=>{
            headerNav.classList.toggle("header-nav-hide");
            burgerMenu.classList.toggle("open");
            burgerMenu.classList.toggle("close");
            main.classList.toggle('hide');
        })
    }
    
    // dys button
    if(dysButton.value !== "off"){
        body.id = 'dys';
        dysButton.classList.add('toggle-button-on');
    }else{
        body.id = '';
        dysButton.classList.remove('toggle-button-on');
    }
    dysButton.addEventListener('click', ()=>{
        if(dysButton.value !== "off"){
            dysButton.classList.add('toggle-button-on');
            body.id = 'dys';
        }else{
            body.id = '';
            dysButton.classList.remove('toggle-button-on');
        }
    })

    if(userDelete){
        const p = document.querySelector('.espace-admin-modal p');
        const modal = document.querySelector(".espace-admin-modal");
        const no = document.querySelector(".espace-admin-modal-no");
        const input = document.querySelector(".espace-admin-modal-user-email");
        userDelete.addEventListener('click', ()=>{
            console.log(modal);
            p.innerText+=userDelete.id + " ?";
            input.value = userDelete.id;
            modal.classList.remove('hide');
        })
        no.addEventListener('click', ()=>{
            modal.classList.add('hide');
        })
    }
})