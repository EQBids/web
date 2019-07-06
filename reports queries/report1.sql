select  s.name , bo.bid_id, concat(u.first_name , u.last_name) as contractor, e.name as Equipment, bo.price, oi.qty, o.created_at as OrderDate , if(b.status=1,'Active', if(b.status = 2, 'Canceled', 'Closed')) as status, oi.deliv_date, oi.return_date from orders o
inner join order_supplier os on os.order_id = o.id
inner join suppliers s on s.id = os.supplier_id
inner join order_items oi on oi.order_id = o.id
inner join equipments e on e.id = oi.equipment_id
inner join bid_order_item bo on bo.order_item_id = oi.id
inner join bids b on b.id = bo.bid_id
inner join users u on u.id = o.user_id
inner join contractors co on co.user_id = u.id
where s.id = '18'
group by bo.bid_id
order by bo.bid_id