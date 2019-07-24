select o.id, concat(u.first_name, u.last_name) as name, s.name , oi.qty,oi.deliv_date, oi.return_date, b.price as bid from equipments e
inner join order_items oi on oi.equipment_id = e.id
inner join orders o on o.id = oi.order_id 
inner join users u on u.id = o.user_id
inner join sites s on o.site_id = s.id
left join bid_order_item b on b.order_item_id = oi.id
where e.id = 101