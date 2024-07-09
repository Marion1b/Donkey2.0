export class Tickets{
    days;
    constructor(days){
        this.days = days;
    }

    get days(){
        return this.days;
    }

    set days(days){
        this.days = days;
    }

    total(){
        let totalTickets = 0;
        let totalPay = 0;
        let number;
        let type;
        let price=0;
        for(let i in this.days){
            if(this.days[i].value){
                type = this.days[i].id;
                number = parseInt(this.days[i].value);
                totalTickets = number;
                switch(type){
                    case 'friday':
                        price = 24;
                        break;
                    case 'saturday':
                    case 'sunday':
                    case 'friday-sunday-reduce':
                    case 'friday-saturday-reduce':
                        price = 32;
                        break;
                    case 'friday-saturday':
                    case 'friday-sunday':
                    case 'all-days-reduce':
                        price = 50;
                        break;
                    case 'saturday-sunday':
                        price = 58;
                        break;
                    case 'all-days':
                        price = 80;
                        break;
                    case 'friday-reduce':
                        price = 14;
                        break;
                    case 'saturday-reduce':
                    case 'sunday-reduce':
                        price = 22;
                        break;
                    case 'saturday-sunday-reduce':
                        price = 40;
                        break;
                    default:
                        price = 0;
                }
                totalPay += price * totalTickets;
            }
        }
        return totalPay;
    }
}