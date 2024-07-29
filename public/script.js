import { Tickets } from "./Tickets.js";

document.addEventListener('DOMContentLoaded', function(){
    const buyTickets = document.querySelector(".buy-tickets");
    const burgerMenu = document.querySelector(".burger-menu");
    const main = document.querySelector('main');
    const body = document.querySelector('body');
    const dysButton = document.querySelectorAll('#dys-font');
    const userDelete = document.querySelectorAll('.user-delete');
    const ticketDelete = document.querySelectorAll('.ticket-delete');
    const formImg = document.querySelectorAll('.form img');
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
    for(let i =0; i<dysButton.length; i++){
        if(dysButton[i].value === "on"){
            body.id = 'dys';
            dysButton[i].classList.add('toggle-button-on');
        }else{
            body.id = '';
            dysButton[i].classList.remove('toggle-button-on');
        }
        dysButton[i].addEventListener('click', ()=>{
            if(dysButton[i].value === "on"){
                dysButton[i].classList.add('toggle-button-on');
                body.id = 'dys';
            }else{
                body.id = '';
                dysButton[i].classList.remove('toggle-button-on');
            }
        })
    }

    if(userDelete.length > 0){
        const p = document.querySelector('.espace-admin-modal p');
        const modal = document.querySelector(".espace-admin-modal");
        const no = document.querySelector(".espace-admin-modal-no");
        const input = document.querySelector(".espace-admin-modal-user-email");
        for(let i =0; i<userDelete.length; i++){
            userDelete[i].addEventListener('click', ()=>{
                p.innerText+=userDelete[i].id + " ?";
                input.value = userDelete[i].id;
                modal.classList.remove('hide');
            })
        }
        no.addEventListener('click', ()=>{
            modal.classList.add('hide');
        })
    }

    if(ticketDelete.length > 0){
        console.log("coucou");
        const p = document.querySelector(".espace-admin-ticket-modal p");
        const modal = document.querySelector(".espace-admin-ticket-modal");
        const no = document.querySelector(".espace-admin-ticket-modal-no");
        const input = document.querySelector(".espace-admin-modal-ticket-id");
        for(let i=0; i<ticketDelete.length; i++){
            ticketDelete[i].addEventListener('click', ()=>{
                p.innerText+=ticketDelete[i].id + "?";
                input.value = ticketDelete[i].id;
                modal.classList.remove('hide');
            })
        }
        no.addEventListener('click', ()=>{
            modal.classList.add('hide');
        })
    }

    // shapes follows mouse movment
    if(formImg.length > 0){
        main.addEventListener('mousemove', (e)=>{
            let x = e.clientX;
            let y = e.clientY;
            for(let i=0; i<formImg.length; i++ ){
                formImg[i].style.transform = `translate(${x/50}px, ${y/50}px)`;
            }
        })
    }
})