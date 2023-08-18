import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-adminpage',
  templateUrl: './adminpage.component.html',
  styleUrls: ['./adminpage.component.css']
})
export class AdminpageComponent implements OnInit{

  auth: any;
  constructor(
    private router: Router
  ){}
  
  ngOnInit(): void{
    this.auth = localStorage.getItem('tokenAdmin');
  
    if(!this.auth){
      this.router.navigate(['/login']);
    }
  }

}
