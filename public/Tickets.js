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
        for(let i in this.days){
            if(this.days[i].value){
                number = parseInt(this.days[i].value);
                total += number;
            }
        }
        return totalPay;
    }
}